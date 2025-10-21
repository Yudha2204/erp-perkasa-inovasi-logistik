<?php

namespace Modules\FinancePiutang\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTax;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Illuminate\Support\Facades\Validator;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\BankAccount;
use Modules\FinancePiutang\App\Models\InvoiceDetail;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePiutang\App\Models\RecieveDetail;
use Modules\FinancePiutang\App\Models\SalesOrderHead;
use Modules\Notification\App\Models\NotificationCustom;
use Modules\ReportFinance\App\Models\Sao;
use Modules\Setting\App\Models\LogoAddress;
use Modules\FinanceDataMaster\App\Models\TermPaymentContact;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;
class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-invoice@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-invoice@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-invoice@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-invoice@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoice = InvoiceHead::whereNot('status', 'Beginning Balance')->get();
        $bank = BankAccount::all();
        foreach($invoice as $i) {
            $i->updateStatus();
        }
        return view('financepiutang::invoice.index', compact('invoice', "bank"));
    }

    public function getJurnal($id)
    {
        $data = InvoiceHead::find($id);
        return view('financepiutang::invoice.jurnal', compact('data'));
    }

    public function getPdf(Request $request){
        $invoice_id = $request->invoice_id;
        $invoiceData = InvoiceHead::find($invoice_id);
        $invoiceDetail = InvoiceDetail::where('head_id', $invoice_id)->get();
        $shipper = $request->shipper;
        $consignee = $request->consignee;
        $comodity = $request->comodity;
        $mbl = $request->mbl;
        $hbl = $request->hbl;
        $voyage = $request->voyage;
        $invoice_date = $request->invoice_date;
        $depart_date = $request->depart_date;
        $origin = $request->origin;
        $weight = $request->weight;
        $volumetrik = $request->volumetrik;
        $m3 = $request->m3;
        $chargetableWeight = $request->chargetableWeight;

        $photos = LogoAddress::first();

        $currencies = $request->input('currencies', []);
        $filterFormCurrency = [];
        foreach ($currencies as $currency) {
            $bank = BankAccount::find($currency);

            $formCurrency = [
                "fund" => $bank->account_no,
                "bankName" => $bank->bank_name,
                "address" => $bank->address,
                "code" => $bank->swift_code,
                "currency" => $bank->currency->initial
            ];

            $filterFormCurrency[$currency] = $formCurrency;
        };

        $contact = MasterContact::find($invoiceData->contact_id);
        $dataBillTo = [];
        if($contact){
            $dataBillTo = [$contact->customer_name, $contact->company_name, $contact->address . "," . $contact->city . "," . $contact->country, $contact->postal_code, $contact->phone_number];
        }
        $dataBillTo = array_filter($dataBillTo, function ($value) {
            return !is_null($value);
        });
        $sales = SalesOrderHead::find($invoiceData->sales_id);
        $currency = MasterCurrency::find($sales->currency_id);
        return view('financepiutang::invoice.softFilePdf', compact(
            'invoiceData', 'invoiceDetail', 'contact', 'currency', 'shipper', 'consignee', 'comodity', 'mbl', 'hbl', 'voyage',
            'invoice_date', 'depart_date', 'origin', 'weight', 'volumetrik', 'm3', 'chargetableWeight' , 'filterFormCurrency', 'dataBillTo', 'photos'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contact = MasterContact::whereJsonContains('type','1')->get();
        // $terms = MasterTermOfPayment::all();
        $terms = TermPaymentContact::with(['term_payment'])->select('term_payment_id')->distinct()->get();
        $taxs = MasterTax::where('status',1)->get();
        // filter hanya yang tax_type = 'ppn'
        $ppn_tax = $taxs->where('type', 'PPN');
        $taxs = $taxs->where('type', 'PPH');
        return view('financepiutang::invoice.create', compact('contact', 'terms', 'taxs' , 'ppn_tax'));
    }

    public function getSalesOrder(Request $request)
    {
        $id = $request->id;
        $invoice = InvoiceHead::whereNot('status', 'Beginning Balance')->pluck('sales_id');
        $salesOrder = SalesOrderHead::where('contact_id', $id)
                            ->whereNotIn('id', $invoice)
                            ->get();

        return response()->json([
            'message' => 'Success',
            'data'    => $salesOrder
        ]);
    }

    public function getTermByContact(Request $request, $id)
    {
        $terms = TermPaymentContact::with(['term_payment'])->select('term_payment_id')->where('contact_id', $id)->distinct()->get();

        $data = [];
        foreach ($terms as $term) {
            $data[] = [
                'id' => $term->term_payment->id,
                'name' => $term->term_payment->name,
                'pay_days' => $term->term_payment->pay_days,
            ];
        }

        return response()->json([
            'message' => 'Success',
            'data'    => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'customer_id'   => 'required',
                'sales_no' => 'required',
                'no_transactions'    => 'required',
                'date_invoice' => 'required',
                'term_payment' => 'required',
                // 'coa_ar' => 'required'
            ], [
                "customer_id.required" => "The customer field is required.",
                "sales_no.required" => "The sales no field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_invoice.required" => "The date is required.",
                "term_payment.required" => "The term of payment field is required.",
                "coa_ar.required" => "Account receive field is required."
            ]);
            // dd($request->all());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $contact_id = $request->input('customer_id');
            $sales_id = $request->input('sales_no');
            $currency_id = SalesOrderHead::find($sales_id)->currency_id;
             // Check Exchange Rate
            //  if($currency_id != 1){
            //     $exchange_rate = ExchangeRate::whereDate('date', $request->input('date_invoice'))->
            //     where(function ($query) use ($currency_id) {
            //         $query->where('from_currency_id', '=', $currency_id)
            //               ->orWhere('to_currency_id', '=', $currency_id);
            //     })
            //     ->first();
            //     if (!$exchange_rate) {
            //         toast('Exchange rate not found for this date!','error');
            //         return redirect()->back()
            //             ->withErrors(['exchange_rate' => 'Exchange rate for this date is not available.']);
            //             // ->withInput();
            //     }
            //  }
            $term_payment = $request->input('term_payment');
            $no_transactions = $request->input('no_transactions');
            $date_invoice = $request->input('date_invoice');
            $sell_des = $request->input('sell_des');
            $discount_type = $request->input('discount_type');
            $coa_ar = $request->input('coa_ar');
            $discount_nominal = $this->numberToDatabase($request->input('discount'));
            // $additional_cost = $this->numberToDatabase($request->input('additional_cost'));

            $total_display = $this->numberToDatabase($request->input('total_display'));
            $display_pajak = $this->numberToDatabase($request->input('display_pajak'));
            $display_dp = $this->numberToDatabase($request->input('display_dp'));
            $discount_display = $this->numberToDatabase($request->input('discount_display'));
            $ppn_tax = $request->input('ppn_tax') ? explode(":",$request->input('ppn_tax'))[0] : null;
            $exp_term = explode(":", $term_payment);
            $exp_transaction = explode("-", $no_transactions);
            $number = $exp_transaction[3];

            $data = [
                'contact_id' => $contact_id,
                'sales_id' => $sales_id,
                'term_payment' => $exp_term[0],
                'number' => $number,
                'currency_id' => $currency_id,
                'date_invoice' => $date_invoice,
                'description' => $sell_des,
                'account_id' => $coa_ar,
                'tax_id' => $ppn_tax,
                // 'additional_cost' => $additional_cost,
                'discount_type' => $discount_type,
                'discount_nominal' => $discount_nominal,
                'status' => "open",
            ];

            // $piutang_usaha_id = MasterAccount::where('code', '110100')
            //                         ->where('account_name', 'Piutang Usaha')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$piutang_usaha_id) {
            //     return redirect()->back()->withErrors(['piutang_usaha' => 'Please add the account of Piutang Usaha with code number is 110100']);
            // }
            // $piutang_usaha_id = $piutang_usaha_id->id;

            // 13092025 ganti ke sales discount
            $diskon_penjualan_id = MasterAccount::where('account_type_id', 16)->first();

            if(!$diskon_penjualan_id) {
                return response()->json(['errors' => ['pendapatan_lain' => ['Please add the account of Sales Discount']]], 422);
            }
            $diskon_penjualan_id = $diskon_penjualan_id->id;
            // $ppn_keluaran_id = MasterAccount::where('code', "220500")
            //                         ->where('account_name', 'PPN Keluaran')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$ppn_keluaran_id) {
            //     return redirect()->back()->withErrors(['ppn_keluaran' => 'Please add the account of PPN Keluaran with code number is 220500']);
            // }
            // $ppn_keluaran_id = $ppn_keluaran_id->id;

            // $pendapatan_lain_id = MasterAccount::where('code', "440010")
            //                         ->where('account_name', 'Pendapatan Lain')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$pendapatan_lain_id) {
            //     return redirect()->back()->withErrors(['pendapatan_lain' => 'Please add the account of Pendapatan Lain with code number is 440010']);
            // }
            // $pendapatan_lain_id = $pendapatan_lain_id->id;

            // $pendapatan_jasa_id = MasterAccount::where('code', "440000")
            //                         ->where('account_name', 'Pendapatan Jasa')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$pendapatan_jasa_id) {
            //     return redirect()->back()->withErrors(['pendapatan_jasa' => 'Please add the account of Pendapatan Jasa with code number is 440000']);
            // }
            // $pendapatan_jasa_id = $pendapatan_jasa_id->id;

            // $kas_id = MasterAccount::where('code', "110001")
            //                         ->where('account_name', 'Kas')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$kas_id) {
            //     return redirect()->back()->withErrors(['kas' => 'Please add the account of Kas with code number is 110001']);
            // }
            // $kas_id = $kas_id->id;

            // $prepaid_sales = MasterAccount::where('account_name', 'Prepaid Sales')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$prepaid_sales) {
            //     return redirect()->back()->withErrors(['coa_ps' => 'Please add the account of Prepaid Sales']);
            // }
            // $prepaid_sales = $prepaid_sales->id;

            InvoiceHead::create($data);
            $newestInvoice = InvoiceHead::latest()->first();
            $head_id = $newestInvoice->id;

            $totBalance = 0;
            $formData = json_decode($request->input('form_data'), true);
            $grand_dp = 0;
            $tax_journal = [];
            $sales_jourjal = [];
            $totalDetailWithoutDiscount = 0;
            $totalDiscount = 0;
            foreach ($formData as $data) {
                $des_detail = $data['des_detail'];
                $renark_detail = $data['remark_detail'];
                $qty_detail = $this->numberToDatabase($data['qty_detail']);
                $uom_detail = $data['uom_detail'];
                $pajak_detail = $data['pajak_detail'];
                $price_detail = $this->numberToDatabase($data['price_detail']);
                $discount_detail = $this->numberToDatabase($data['disc_detail']);
                $disc_type_detail = $data['disc_type_detail'];
                $sales_acc_id = $data['coa_sales'];
                $exp_tax = explode(":", $pajak_detail);
                $tax_id = $exp_tax[0];

                // $is_dp = $data['dp_desc'];
                // $dp_type_detail = null;
                // $dp_detail = null;
                // if($is_dp == 1) {
                //     $dp_type_detail = $data['dp_type_detail'];
                //     $dp_detail = $this->numberToDatabase($data['dp_detail']);
                // }

                $totBalance += ($price_detail*$qty_detail);
                $totalFull = ($price_detail*$qty_detail);
                $discTotal = 0;
                if($disc_type_detail === "persen") {
                    $discTotal = ($discount_detail/100)*$totalFull;
                }else{
                    $discTotal = $discount_detail;
                }
                $totalDiscount += $discTotal;
                $totalFull -= $discTotal;
                $pajak = 0;

                if(!$tax_id) {
                    $tax_id = null;
                }else{
                    $tax = MasterTax::find($tax_id);

                    // if($tax_id == 2 ) {
                        $pajak += (($tax->tax_rate/100) * $totalFull);
                    // } else if($tax_id == 3 ) {
                    // }
                    // $totalFull -= $pajak;
                    // if($tax->tax_rate > 0 && !$tax->account_id){
                    //     DB::rollBack();
                    //     // dd($e->getMessage());
                    //         return redirect()->back()
                    //             ->withErrors(['error' => 'Add the account to tax if rate more than 0']);
                    // }else if($tax->account_id){
                    //     $tax_journal[] = [0, $pajak , $tax->account_id ,$tax->id];
                    // }else if($tax->tax_rate == 0 && !$tax->account_id ){
                    //     // Skip
                    // }
                }
                // $dp = 0;
                // if($dp_type_detail == "persen") {
                //     $dp += ($dp_detail/100)*$totalFull;
                // } else {
                //     $dp += $dp_detail;
                // }
                // $grand_dp += $dp;
                $totalDetailWithoutDiscount += $totalFull;
                $sales_journal[] = [0,(($price_detail*$qty_detail) ),$sales_acc_id];
                InvoiceDetail::create([
                    'head_id' => $head_id,
                    'description' => $des_detail,
                    'quantity' => $qty_detail,
                    'uom' => $uom_detail,
                    'price' => $price_detail,
                    'tax_id' => $tax_id,
                    'remark' => $renark_detail,
                    'discount_type' => $disc_type_detail,
                    'discount_nominal' => $discount_detail,
                    'account_id' => $sales_acc_id
                    // 'dp_type' => $dp_type_detail,
                    // 'dp_nominal' => $dp_detail
                ]);
            }
            // $totalDiscou
            $ppn_tax_id = $request->input('ppn_tax') ? explode(":",$request->input('ppn_tax'))[0] : null;
            $ppn_tax = MasterTax::find($ppn_tax_id);
            if ($ppn_tax && $ppn_tax->account_id) {
                $total_after_discount = $total_display;
                $ppn_amount = $total_after_discount - ($total_after_discount /(1 + ($ppn_tax->tax_rate / 100)));
                $tax_journal[] = [0, $ppn_amount, $ppn_tax->account_id, $ppn_tax->id];
            }
            $discount_value = ($discount_nominal == 0 || is_null($discount_nominal))
    ? 0
    : ($totalDetailWithoutDiscount * ($discount_nominal / 100));
            $totalDiscount += $discount_value;
            $flow = [
                //debit, kredit
                // [$total_display, 0, $piutang_usaha_id],
                [($total_display), 0, $coa_ar],
                [$totalDiscount, 0, $diskon_penjualan_id],
                // [$display_pajak, 0, $ppn_keluaran_id],
                // [$display_dp, 0, $kas_id],
                // [0, $display_dp, $dp_id],
                // [0, 0, $prepaid_sales],
                // [0, $additional_cost, $pendapatan_lain_id],
                // [0, $totBalance, $coa_sales]
            ];
            // $ppn_tax = MasterTax::find($ppn_tax);

            $flow = [
                ...$flow,
                ...$sales_journal,
                ...$tax_journal,
            ];
            // DB::rollBack();
            // dd($flow , $sales_journal, $tax_journal);
            foreach ($flow as $item) {
                $cashflowData = [
                    'transaction_id' => $head_id,
                    'master_account_id' => $item[2],
                    'transaction_type_id' => 3,
                    'currency_id' => $currency_id,
                    "date" => $date_invoice,
                    'debit' => $item[0],
                    'credit' => $item[1]
                ];
            //     DB::rollBack();
            // dd($cashflowData);
                BalanceAccount::create($cashflowData);
            }

            $remain = $total_display - $grand_dp;
            $dataSao = [
                "invoice_id" => $head_id,
                "contact_id" => $contact_id,
                "currency_id" => $currency_id,
                "date" => $date_invoice,
                "account" => "piutang",
                "total" => $total_display,
                "already_paid" => $grand_dp,
                "remaining" => $remain,
                "isPaid" => false,
                "type" => "customer",
            ];
            Sao::create($dataSao);
            DB::commit();
            return response()->json(['message' => 'create successfully!'], 200);
            //code...
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['error' => 'Error On App Please Contact IT Support'], 500);
            //throw $th;
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $invoiceHead = InvoiceHead::find($id);
        return view('financepiutang::invoice.read', compact('invoiceHead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $receive = RecieveDetail::where('invoice_id', $id)->first();
        if($receive) {
            toast('Failed to Update Data!', 'error');
            return redirect()->route('finance.piutang.invoice.index')->withErrors(["error" => "There is receive payment link to this invoice"]);
        }
        $invoice = InvoiceHead::find($id);
        // dd($invoice->jurnal);
        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $taxs = MasterTax::all();
        $ppn_tax = $taxs->where('type', 'PPN');
        $taxs = $taxs->where('type', 'PPH');
        // return response()->json($ppn_tax);
        $invoiceWhere = InvoiceHead::all()->pluck('id');
        $salesOrder = SalesOrderHead::where('contact_id', $id)
                            ->whereNotIn('id', $invoiceWhere)
                            ->get();

        $accounts = MasterAccount::whereIn('account_type_id', [4, 15])
        // ->where('master_currency_id', $invoice->sales->currency_id)
        ->get()
        ->groupBy('account_type_id');

        $coa_ar = $accounts->get(4, collect());
        $coa_sales = $accounts->get(15, collect());
        // return response()->json($invoice->jurnal);
        $grouped = $invoice->jurnal->groupBy(fn($item) => $item->master_account->account_type_id);
        $coa_ar_selected = $grouped->get('4', collect())[0]; // misalnya code 1001
        $coa_sales_selected = $grouped->get('15', collect())[0]; // misalnya code 2001
        // return response()->json([$coa_ar_selected , $coa_sales_selected]);

        return view('financepiutang::invoice.update', compact('contact', 'terms', 'taxs','ppn_tax', 'invoice', 'salesOrder','coa_ar' , 'coa_sales' ,'coa_ar_selected' ,'coa_sales_selected'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'customer_id'   => 'required',
                'sales_no' => 'required',
                'no_transactions'    => 'required',
                'date_invoice' => 'required',
                'term_payment' => 'required',
                'coa_ar' => 'required',
            ], [
                "customer_id.required" => "The customer field is required.",
                "sales_no.required" => "The sales no field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_invoice.required" => "The date is required.",
                "term_payment.required" => "The term of payment field is required.",
                "coa_ar.required" => "Account receive field is required."
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $contact_id = $request->input('customer_id');
            $sales_id = $request->input('sales_no');
            $currency_id = SalesOrderHead::find($sales_id)->currency_id;
            $term_payment = $request->input('term_payment');
            $no_transactions = $request->input('no_transactions');
            $date_invoice = $request->input('date_invoice');
            $sell_des = $request->input('sell_des');
            $discount_type = $request->input('discount_type');
            $discount_nominal = $this->numberToDatabase($request->input('discount'));
            // $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
            $coa_ar = $request->input('coa_ar');
            $total_display = $this->numberToDatabase($request->input('total_display'));
            $display_pajak = $this->numberToDatabase($request->input('display_pajak'));
            $display_dp = $this->numberToDatabase($request->input('display_dp'));
            $discount_display = $this->numberToDatabase($request->input('discount_display'));
            $ppn_tax = $request->input('ppn_tax') ? explode(":",$request->input('ppn_tax'))[0] : null;
            $exp_term = explode(":", $term_payment);
            $exp_transaction = explode("-", $no_transactions);
            $number = $exp_transaction[3];

            $data = [
                'contact_id' => $contact_id,
                'sales_id' => $sales_id,
                'term_payment' => $exp_term[0],
                'number' => $number,
                'currency_id' => $currency_id,
                'date_invoice' => $date_invoice,
                'description' => $sell_des,
                'tax_id' => $ppn_tax,
                'account_id' => $coa_ar,
                // 'additional_cost' => $additional_cost,
                'discount_type' => $discount_type,
                'discount_nominal' => $discount_nominal,
                'status' => "open",
            ];

            $invoice = InvoiceHead::find($id);
            $invoice->update($data);
            $head_id = $invoice->id;

            $totBalance = 0;
            $formData = json_decode($request->input('form_data'), true);
            $grand_dp = 0;
            $totalDiscount = 0;
            foreach ($formData as $data) {
                $des_detail = $data['des_detail'];
                $renark_detail = $data['remark_detail'];
                $qty_detail = $this->numberToDatabase($data['qty_detail']);
                $uom_detail = $data['uom_detail'];
                $pajak_detail = $data['pajak_detail'];
                $price_detail = $this->numberToDatabase($data['price_detail']);
                $sales_acc_id = $data['coa_sales'];
                $discount_detail = $this->numberToDatabase($data['disc_detail']);
                $disc_type_detail = $data['disc_type_detail'];

                $exp_tax = explode(":", $pajak_detail);
                $tax_id = $exp_tax[0];
                if(!$tax_id) {
                    $tax_id = null;
                }

                // $is_dp = $data['dp_desc'];
                // $dp_type_detail = null;
                // $dp_detail = null;
                // if($is_dp == 1) {
                //     $dp_type_detail = $data['dp_type_detail'];
                //     $dp_detail = $this->numberToDatabase($data['dp_detail']);
                // }

                $operator = $data['operator'];
                $exp_operator = explode(":", $operator);

                $totBalance += ($price_detail*$qty_detail);
                $totalFull = ($price_detail*$qty_detail);
                $discTotal = 0;
                if($disc_type_detail === "persen") {
                    $discTotal = ($discount_detail/100)*$totalFull;
                }else{
                    $discTotal = $discount_detail;
                }
                $totalDiscount += $discTotal;
                $totalFull -= $discTotal;
                $pajak = 0;
                if($tax_id == 2 ) {
                    $pajak += (10/100)*$totalFull;
                } else if($tax_id == 3 ) {
                    $pajak += (5/100)*$totalFull;
                }
                $totalFull -= $pajak;
                // $dp = 0;
                // if($dp_type_detail == "persen") {
                //     $dp += ($dp_detail/100)*$totalFull;
                // } else {
                //     $dp += $dp_detail;
                // }
                // $grand_dp += $dp;

                if($exp_operator[1] === "create") {
                    InvoiceDetail::create([
                        'head_id' => $head_id,
                        'description' => $des_detail,
                        'quantity' => $qty_detail,
                        'uom' => $uom_detail,
                        'price' => $price_detail,
                        'tax_id' => $tax_id,
                        'remark' => $renark_detail,
                        'account_id' => $sales_acc_id,
                        'discount_type' => $disc_type_detail,
                        'discount_nominal' => $discount_detail,
                        // 'dp_type' => $dp_type_detail,
                        // 'dp_nominal' => $dp_detail
                    ]);
                } else if($exp_operator[1] === "update") {
                    $invoiceDetail = InvoiceDetail::find($exp_operator[0]);
                    $invoiceDetail->update([
                        'description' => $des_detail,
                        'quantity' => $qty_detail,
                        'uom' => $uom_detail,
                        'price' => $price_detail,
                        'tax_id' => $tax_id,
                        'remark' => $renark_detail,
                        'account_id' => $sales_acc_id,
                        'discount_type' => $disc_type_detail,
                        'discount_nominal' => $discount_detail,
                        // 'dp_type' => $dp_type_detail,
                        // 'dp_nominal' => $dp_detail
                    ]);
                } else if($exp_operator[1] === "delete") {
                    $invoiceDetail = InvoiceDetail::find($exp_operator[0]);
                    if($invoiceDetail) {
                        $invoiceDetail->delete();
                    }
                }
            }

            // $invoice->jurnal()->delete();
            BalanceAccount::where('transaction_type_id', 3)
              ->where('transaction_id', $invoice->id)
              ->forceDelete();


            $tax_journal = [];
            $sales_journal = [];
            $totalDetailWithoutDiscount = 0;
            // DB::rollBack();
            // return response()->json($invoice->details);
            foreach($invoice->details as $d) {
                $totalFull = ($d->price*$d->quantity);
                $discTotal = 0;
                if($d->discount_type === "persen") {
                    $discTotal = ($d->discount_nominal/100)*$totalFull;
                }else{
                    $discTotal = $d->discount_nominal;
                }
                $totalFull -= $discTotal;
                $pajak = 0;

                // if(!$d->tax_id) {
                //     $tax_id = null;
                // }else{
                //     $tax = MasterTax::find($d->tax_id);
                //     $pajak += ($tax->tax_rate/100) * $totalFull;
                //     $totalFull -= $pajak;
                //     if($tax->tax_rate > 0 && !$tax->account_id){
                //         DB::rollBack();
                //         // dd($e->getMessage());
                //             return redirect()->back()
                //                 ->withErrors(['error' => 'Add the account to tax if rate more than 0']);
                //     }else if($tax->account_id){
                //         $tax_journal[] = [0, $pajak , $tax->account_id ];
                //     }else if($tax->tax_rate == 0 && !$tax->account_id ){
                //         // Skip
                //     }
                // }
                $totalDetailWithoutDiscount +=($totalFull);
                $sales_journal[] = [0,(($d->price*$d->quantity)),$d->account_id];
            }

            $diskon_penjualan_id = MasterAccount::where('account_type_id', 16)->first();

            $diskon_penjualan_id = $diskon_penjualan_id->id;
            if(!$diskon_penjualan_id) {
                return response()->json(['errors' => ['pendapatan_lain' => ['Please add the account of Sales Discount']]], 422);
            }
            // $prepaid_sales = MasterAccount::where('account_name', 'Prepaid Sales')
            //                         ->where('master_currency_id', $currency_id)->first();
            // if(!$prepaid_sales) {
            //     return redirect()->back()->withErrors(['coa_ps' => 'Please add the account of Prepaid Sales']);
            // }
            // $prepaid_sales = $prepaid_sales->id;

            $ppn_tax_id = $request->input('ppn_tax') ? explode(":",$request->input('ppn_tax'))[0] : null;
            $ppn_tax = MasterTax::find($ppn_tax_id);

            if ($ppn_tax && $ppn_tax->account_id) {
                $total_after_discount = $total_display;
                $ppn_amount = $total_after_discount - ($total_after_discount /(1 + ($ppn_tax->tax_rate / 100)));
                $tax_journal[] = [0, $ppn_amount, $ppn_tax->account_id, $ppn_tax->id];
            }
            $discount_value = ($discount_nominal == 0 || is_null($discount_nominal))
            ? 0
            : ($totalDetailWithoutDiscount * ($discount_nominal / 100));
                    $totalDiscount += $discount_value;
            $flow = [
                //debit, kredit
                [($total_display), 0, $coa_ar],
                [$totalDiscount, 0, $diskon_penjualan_id],
                // [0, 0, $prepaid_sales],
            ];

            $flow = [
                ...$flow,
                ...$sales_journal,
                ...$tax_journal
            ];

            foreach ($flow as $item) {
                $cashflowData = [
                    'transaction_id' => $head_id,
                    'master_account_id' => $item[2],
                    'transaction_type_id' => 3,
                    'currency_id' => $currency_id,
                    "date" => $date_invoice,
                    'debit' => $item[0],
                    'credit' => $item[1]
                ];
                BalanceAccount::create($cashflowData);
            }

            $remain = $total_display - $grand_dp;
            $dataSao = [
                "invoice_id" => $head_id,
                "contact_id" => $contact_id,
                "currency_id" => $currency_id,
                "date" => $date_invoice,
                "account" => "piutang",
                "total" => $total_display,
                "already_paid" => $grand_dp,
                "remaining" => $remain,
                "isPaid" => false,
                "type" => "customer",
            ];
            $tagertSao = Sao::where('invoice_id', $id)->first();
            $tagertSao->update($dataSao);
            DB::commit();

            return response()->json(['message' => 'update successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['error' => 'Error On App Please Contact IT Support'], 500);
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $receive = RecieveDetail::where('invoice_id', $id)->first();
        if($receive) {
            toast('Failed to Delete Data!', 'error');
            return redirect()->route('finance.piutang.invoice.index')->withErrors(["error" => "There is receive payment link to this invoice"]);
        }
        BalanceAccount::where('transaction_id', $id)
            ->where('transaction_type_id', 3)->delete();
        InvoiceDetail::where('head_id', $id)->delete();
        $invoice = InvoiceHead::findOrFail($id);
        $notification = NotificationCustom::where('remark', $invoice->transaction)->first();
        if($notification) {
            $notification->delete();
        }
        $invoice->delete();

        toast('Data Deleted Successfully!', 'success');
        return redirect()->back()->with('success', 'delete successfully!');
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
