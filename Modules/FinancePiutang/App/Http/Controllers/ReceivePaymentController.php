<?php

namespace Modules\FinancePiutang\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinanceDataMaster\App\Models\MasterTax;

use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePiutang\App\Models\SalesOrderHead;

use Illuminate\Support\Facades\Validator;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinancePiutang\App\Models\RecieveDetail;
use Modules\FinancePiutang\App\Models\RecieveHead;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;
use Modules\ReportFinance\App\Models\Sao;
use Illuminate\Support\Facades\DB;

class ReceivePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-receive_payment@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-receive_payment@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-receive_payment@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-receive_payment@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_recieve = RecieveHead::all();
        return view('financepiutang::receive-payment.index',compact('data_recieve'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();
        $accountTypes = AccountType::all();

        $current_year = Carbon::now()->year;
        $receive = RecieveHead::whereYear('date_recieve', $current_year)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($receive) {
            $latest_number = $receive->number + 1;
        }

        return view('financepiutang::receive-payment.create', compact('contact', 'terms', 'currencies', 'accountTypes', 'latest_number'));
    }

    public function getTransaction(Request $request)
    {
        $date = $request->get('date');
        $receive = RecieveHead::whereYear('date_recieve', Carbon::parse($date)->year)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($receive) {
            $latest_number = $receive->number + 1;
        }

        return response()->json([
            'message' => "Success",
            'data' => $latest_number
        ]);
    }

    public function getSalesOrder(Request $request){
        $customer = $request->customer;
        $currency = $request->currency;
        $job_order = $request->job_order;

        $salesOrder = [];
        if($currency && $customer) {
            if($job_order && $job_order !== "null") {
                $exp_jo = explode(":", $job_order);
                $job_order_id = $exp_jo[0];
                $job_order_source = $exp_jo[1];
                $salesOrder = SalesOrderHead::where('contact_id', $customer)
                    ->where('currency_id', $currency)
                    ->where('marketing_id', $job_order_id)
                    ->where('source', $job_order_source)
                    ->get();
            } else {
                $salesOrder = SalesOrderHead::where('contact_id', $customer)
                    ->where('currency_id', $currency)
                    ->where('marketing_id', null)
                    ->get();
            }
        }

        return $salesOrder;
    }

    public function getInvoice(Request $request) {
        $salesOrder = $this->getSalesOrder($request);

        $invoices = [];
        foreach($salesOrder as $s) {
            $invoice = InvoiceHead::where('sales_id', $s->id)
                        ->where('status', '!=', 'paid')
                        ->first();
            if($invoice) {
                $invoices[] = $invoice;
            }
        }

        return response()->json([
            'message' => "Success",
            'data'    => $invoices
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
                'head_account_id' => 'required',
                'date_recieve'    => 'required',
                'currency_head_id' => 'required',
                'no_transactions' => 'required'
            ], [
                "customer_id.required" => "The customer field is required.",
                "head_account_id.required" => "The account name field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_recieve.required" => "The date is required.",
                "currency_head_id.required" => "The currency field is required."
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $customer_id = $request->input('customer_id');
            $head_account_id = $request->input('head_account_id');
            $date_recieve = $request->input('date_recieve');
            $currency_id = $request->input('currency_head_id');

            $validator = MasterAccount::where('id', $head_account_id)->where('master_currency_id', $currency_id)->get()->first();
            if(!$validator) {
                return response()->json(['errors' => ["error" => ['Please input a valid account']]], 422);
            }

            $no_transactions = $request->input('no_transactions');
            $exp_transaction = explode("-", $no_transactions);
            $number = $exp_transaction[2];

            $description = $request->input('description');
            $is_job_order = $request->input('choose_job_order');
            $job_order_id = null;
            $job_order_source = null;
            if($is_job_order === "1") {
                $job_order = $request->input('job_order_id');
                if($job_order) {
                    $exp_job_order = explode(":", $job_order);
                    if(sizeof($exp_job_order) !== 2) {
                        return response()->json(['errors' => ['no_referensi' => ['Please input a valid no referensi']]], 422);
                    }
                    $job_order_id = $exp_job_order[0];
                    $job_order_source = $exp_job_order[1];
                }
            }

            $note_recieve = $request->input('note_recieve');
            $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
            $grand_total = $this->numberToDatabase($request->input('total_display'));
            $discount_display = $this->numberToDatabase($request->input('discount_display'));
            $display_dp = $this->numberToDatabase($request->input('display_dp'));
            $display_dp_invoice = $this->numberToDatabase($request->input('display_dp_invoice'));

            $status = "open";
            if($display_dp === 0.0) {
                $status = "paid";
            }

            $data = [
                'contact_id' => $customer_id,
                'account_id' => $head_account_id,
                'currency_id' => $currency_id,
                'date_recieve' => $date_recieve,
                'number' => $number,
                'description' => $description,
                'job_order_id' => $job_order_id,
                'source' => $job_order_source,
                'note' => $note_recieve,
                'additional_cost' => $additional_cost,
                'status' => $status,
            ];

            $diskon_penjualan_id = MasterAccount::where('account_type_id', 16)->first();
            if(!$diskon_penjualan_id) {
                return response()->json(['errors' => ['diskon_penjualan' => ['Please add the account of Sales Discount']]], 422);
            }
            $diskon_penjualan_id = $diskon_penjualan_id->id;
            $prepaid_sales_id = MasterAccount::where('account_type_id', 10)->first();
            if(!$prepaid_sales_id) {
                return response()->json(['errors' => ['pendapatan_lain' => ['Please add the account of Prepaid Sales']]], 422);
            }
            $prepaid_sales_id = $prepaid_sales_id->id;
            RecieveHead::create($data);
            $newRecieveHead = RecieveHead::latest()->first();
            $head_id = $newRecieveHead->id;

            $totBalance = 0;
            $isDiscount = true;
            $allDetails = [];
            $tax_journal = [];
            $ar_jourjal = [];
            $account_charges = []; // Track account charges for journal entries
            $formData = json_decode($request->input('form_data'), true);
            $totalDiscount = 0;
            foreach ($formData as $idx => $data) {
                $tmpDiscount = 0;
                $invoice_id = $data["detail_invoice"] ?? null;
                $charge_type = $data["charge_type"] ?? 'invoice';
                
                // Skip if no invoice_id and not account charge
                if(!$invoice_id && $charge_type !== 'account') {
                    continue;
                }
                
                // For invoice charges, check if already processed
                if($charge_type === 'invoice' && $invoice_id && in_array($invoice_id, $allDetails)) {
                    continue;
                }
                
                $remark = $data['detail_remark'];
                $discount_type = $data['detail_discount_type'];
                $discount_nominal = $this->numberToDatabase($data['detail_discount_nominal']);
                $account_id_detail = $data['account_id'];

                $amount = $this->numberToDatabase($data['detail_jumlah']);
                $other_currency = $data['other_currency'];
                $currency_via_id = null;
                $amount_via = null;
                if($other_currency === "1") {
                    $currency_via_id = $data['other_currency_type'];
                    if($currency_via_id) {
                        $amount = $this->numberToDatabase($data['other_currency_nominal']);
                        $exchange = ExchangeRate::find($currency_via_id);
                        $pembagi = $exchange->to_nominal/$exchange->from_nominal;
                        if($exchange->from_currency_id === $newRecieveHead->currency_id) {
                            $pembagi = $exchange->from_nominal/$exchange->to_nominal;
                        }
                        $amount_via = $amount;
                        $amount = $amount*$pembagi;
                    } else {
                        $currency_via_id = null;
                    }
                }

                $totBalance += $amount;

                $is_dp = $data["dp_desc"];
                $dp_type = null;
                $dp_nominal = null;
                if($idx > 0) {
                    if($is_dp == 1 && $isDiscount === true) {
                        continue;
                    } else if($is_dp == 0 && $isDiscount === false) {
                        continue;
                    }
                }
                $total_after_discount = 0;
                $totalWithPPn = 0;
                if($is_dp == 1) {
                    if($idx === 0) {
                        $isDiscount = false;
                    }
                    $dp_type = $data["detail_dp_type"];
                    $dp_nominal = $this->numberToDatabase($data["detail_dp_nominal"]);
                } else {
                    // Only process invoice logic for invoice charges
                    if($charge_type === 'invoice' && $invoice_id) {
                        $invoice = InvoiceHead::find($invoice_id);

                        $invoice->update([
                            "status" => "paid"
                        ]);

                    foreach($invoice->details as $d) {
                        $totalFull = ($d->price*$d->quantity);
                        $discTotal = 0;
                        if($d->discount_type === "persen") {
                            $discTotal = ($d->discount_nominal/100)*$totalFull;
                        }else{
                            $discTotal = $d->discount_nominal;
                        }
                        // $totalDiscount += $discTotal;
                        $tmpDiscount += $discTotal;
                        $totalFull -= $discTotal;
                        $discTotal = 0;

                        $pajak = 0;
                        if(!$d->tax_id) {
                            $tax_id = null;
                        }else{
                            $tax = MasterTax::find($d->tax_id);
                            $pajak += ($tax->tax_rate/100) * $totalFull;
                            $totalFull -= $pajak;
                            if($tax->tax_rate > 0 && !$tax->account_id){
                                DB::rollBack();
                                // dd($e->getMessage());
                                return response()->json(['errors' => ['error' => ['Add the account to tax if rate more than 0']]], 422);
                            }else if($tax->account_id){
                                $grand_total -= $pajak;
                                $tax_journal[] = [$pajak, 0 , $tax->account_id ];

                            }else if($tax->tax_rate == 0 && !$tax->account_id ){
                                // Skip
                            }
                        }

                        if($invoice->discount_type === "persen") {
                            $discTotal = ($invoice->discount_nominal/100)*$totalFull;
                        }else{
                            $discTotal = $invoice->discount_nominal;
                        }
                        $tmpDiscount += $discTotal;
                        $totalFull -= $discTotal;
                        $discTotal = 0;
                        $total_after_discount += ($totalFull - $discTotal);

                    }

                    $ppn_tax = MasterTax::find($invoice->tax_id);
                    $discTotal = 0;
                    if ($ppn_tax && $ppn_tax->account_id) {
                        $ppn_amount = $total_after_discount * ($ppn_tax->tax_rate / 100);
                        if($discount_type === "persen") {
                            $discTotal = ($discount_nominal/100)*($total_after_discount + $ppn_amount);
                            $ar_journal[] = [0,($total_after_discount + $ppn_amount) - (($discTotal * 2) )  ,$account_id_detail];
                        }else{
                            $discTotal = $discount_nominal;
                            $ar_journal[] = [0,($total_after_discount + $ppn_amount) - (($discTotal * 2) )  ,$account_id_detail];
                        }
                        $totalDiscount += $discTotal;
                        $grand_total -= $ppn_amount;
                        $tax_journal[] = [$ppn_amount, 0, $ppn_tax->account_id, $ppn_tax->id];
                    }else{
                        if($discount_type === "persen") {
                            $discTotal = ($discount_nominal/100)*$total_after_discount;
                            $ar_journal[] = [0,$total_after_discount  - (($discTotal * 2))  ,$account_id_detail];
                        }else{
                            $discTotal = $discount_nominal;
                            $ar_journal[] = [0,$total_after_discount  - (($discTotal * 2))  ,$account_id_detail];
                        }
                        $totalDiscount += $discTotal;
                    }

                    Sao::where('invoice_id', $invoice_id)->update([
                        'isPaid' => true,
                    ]);

                    $receives = RecieveHead::whereHas('details', function($query) use ($invoice_id) {
                        $query->where('invoice_id', $invoice_id);
                    })->get();

                    foreach ($receives as $head) {
                        $all_paid = RecieveDetail::where('head_id', $head->id)
                            ->whereHas('invoice', function($query) {
                                $query->where('status', '!=', 'paid');
                            })
                            ->doesntExist();

                        if ($all_paid) {
                            $head->update(['status' => 'paid']);
                        } else {
                            $head->update(['status' => 'open']);
                        }
                    }
                    } // End of invoice processing
                }

                $detail = [
                    'head_id' => $head_id,
                    'invoice_id' => $charge_type === 'invoice' ? $invoice_id : null,
                    'discount_type' => $discount_type,
                    'discount_nominal' => $discount_nominal,
                    'dp_type' => $dp_type,
                    'dp_nominal' => $dp_nominal,
                    'currency_via_id' => $currency_via_id,
                    'amount_via' => $amount_via,
                    'remark' => $remark,
                    'account_id' => $account_id_detail,
                    'charge_type' => $charge_type,
                    'amount' => $charge_type === 'account' ? $amount : null,
                    'description' => $charge_type === 'account' ? ($data['description'] ?? null) : null,
                ];

                // Only add to allDetails if it's an invoice charge
                if($charge_type === 'invoice' && $invoice_id) {
                    $allDetails[] = $invoice_id;
                }

                RecieveDetail::create($detail);
                
                // Collect account charges for journal entries
                if($charge_type === 'account') {
                    $account_charges[] = [
                        'amount' => $amount,
                        'account_id' => $account_id_detail,
                        'description' => $data['description'] ?? null
                    ];
                }
            }

            if($isDiscount === true) {
                $newRecieveHead->update([ "status" => "paid" ]);
            }

            if($display_dp > 0 && $isDiscount === false) {
                $flow = [
                    //debit, kredit
                    [$grand_total, 0, $head_account_id],
                    [0, $display_dp, $prepaid_sales_id]
                ];
            } else {
                $flow = [
                    //debit, kredit
                    [$grand_total , 0, $head_account_id],
                    [$display_dp_invoice, 0, $prepaid_sales_id],
                    [0, $totalDiscount, $diskon_penjualan_id],
                    // [0, $additional_cost, $pendapatan_lain_id],
                    // [0, $totBalance+$display_dp_invoice, $piutang_usaha_id]
                ];
            }
            
            // Add account charges to flow
            $account_charge_flow = [];
            foreach($account_charges as $charge) {
                $account_charge_flow[] = [
                    0, // debit
                    $charge['amount'], // credit
                    $charge['account_id'] // account_id
                ];
            }
            
            $flow = [
                ...$flow,
                ...$ar_journal,
                ...$tax_journal,
                ...$account_charge_flow,
            ];
            // return response()->json($flow);
            foreach ($flow as $item) {
                $cashflowData = [
                    'transaction_id' => $head_id,
                    'master_account_id' => $item[2],
                    'transaction_type_id' => 4,
                    "date" => $date_recieve,
                    "currency_id" => $currency_id,
                    'debit' => $item[0],
                    'credit' => $item[1]
                ];
                BalanceAccount::create($cashflowData);
            }
            DB::commit();

            return response()->json(['message' => 'create successfully!'], 200);
        } catch (Exception $e) {
            //throw $th;
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['error' => 'Error On App Please Contact IT Support'], 500);
        }

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data_recieve = RecieveHead::find($id);
        return view('financepiutang::receive-payment.read',compact('data_recieve'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data_recieve = RecieveHead::find($id);
        if($this->hasLatestReceiveDetail($data_recieve)) {
            return redirect()->back()->withErrors(['error' => 'Please update latest receive payment'])->withInput();
        }

        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();
        $accounts = MasterAccount::where('master_currency_id', $data_recieve->currency_id)->whereIn('account_type_id', [1, 2])->get();
        $accountTypes = AccountType::all();

        $currency_id = $data_recieve->currency_id;
        $export = MarketingExport::whereHas('quotation', function ($query) use ($currency_id) {
                    $query->where('currency_id', $currency_id);
                })
                ->with('quotation')
                ->where('status', 2)
                ->where('contact_id', $data_recieve->contact_id)
                ->get();
        $import = MarketingImport::whereHas('quotation', function ($query) use ($currency_id) {
                    $query->where('currency_id', $currency_id);
                })
                ->with('quotation')
                ->where('status', 2)
                ->where('contact_id', $data_recieve->contact_id)
                ->get();
        $marketing = $export->concat($import);

        $customer = $data_recieve->contact_id;
        $currency = $data_recieve->currency_id;
        $job_order = $data_recieve->job_order_id . ":" . $data_recieve->source;

        $salesOrderQuery = SalesOrderHead::where('contact_id', $data_recieve->contact_id)
            ->where('currency_id', $data_recieve->currency_id);

        if ($data_recieve->job_order_id) {
            $salesOrderQuery->where('marketing_id', $data_recieve->job_order_id)
                            ->where('source', $data_recieve->source);
        } else {
            $salesOrderQuery->where('marketing_id', null);
        }
        $salesOrders = $salesOrderQuery->get();
        $salesOrderIds = $salesOrders->pluck('id');

        // Get unpaid invoices
        $unpaid_invoices = InvoiceHead::whereIn('sales_id', $salesOrderIds)
            ->where('status', '!=', 'paid')
            ->get();

        // Get invoices already on this receive payment
        $current_invoices = InvoiceHead::whereIn('id', $data_recieve->details->pluck('invoice_id'))->get();

        // Combine them and ensure uniqueness
        $selectable_invoices = $unpaid_invoices->merge($current_invoices)->unique('id');

        $exchangeFrom = ExchangeRate::where('from_currency_id', $currency)
                    ->where('date', $data_recieve->date_recieve)
                    ->with(['from_currency','to_currency'])
                    ->get();
        $exchangeTo = ExchangeRate::where('to_currency_id', $currency)
                        ->where('date', $data_recieve->date_recieve)
                        ->with(['from_currency','to_currency'])
                        ->get();
        $exchange = $exchangeFrom->concat($exchangeTo);

        return view('financepiutang::receive-payment.update', compact('contact', 'terms', 'currencies', 'accounts', 'accountTypes', 'data_recieve','marketing','selectable_invoices','exchange'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'customer_id'   => 'required',
                'head_account_id' => 'required',
                'date_recieve'    => 'required',
                'currency_head_id' => 'required',
                'no_transactions' => 'required'
            ], [
                "customer_id.required" => "The customer field is required.",
                "head_account_id.required" => "The account name field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_recieve.required" => "The date is required.",
                "currency_head_id.required" => "The currency field is required."
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $customer_id = $request->input('customer_id');
            $head_account_id = $request->input('head_account_id');
            $date_recieve = $request->input('date_recieve');
            $currency_id = $request->input('currency_head_id');
            $validator = MasterAccount::where('id', $head_account_id)->where('master_currency_id', $currency_id)->get()->first();
            if(!$validator) {
                return response()->json(['errors' => ["error" => ['Please input a valid account']]], 422);
            }

            $no_transactions = $request->input('no_transactions');
            $exp_transaction = explode("-", $no_transactions);
            $number = $exp_transaction[2];

            $description = $request->input('description');
            $is_job_order = $request->input('choose_job_order');
            $job_order_id = null;
            $job_order_source = null;
            if($is_job_order === "1") {
                $job_order = $request->input('job_order_id');
                if($job_order) {
                    $exp_job_order = explode(":", $job_order);
                    if(sizeof($exp_job_order) !== 2) {
                        return response()->json(['errors' => ['no_referensi' => ['Please input a valid no referensi']]], 422);
                    }
                    $job_order_id = $exp_job_order[0];
                    $job_order_source = $exp_job_order[1];
                }
            }

            $note_recieve = $request->input('note_recieve');
            $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
            $grand_total = $this->numberToDatabase($request->input('total_display'));
            $discount_display = $this->numberToDatabase($request->input('discount_display'));
            $display_dp = $this->numberToDatabase($request->input('display_dp'));
            $display_dp_invoice = $this->numberToDatabase($request->input('display_dp_invoice'));

            $status = "open";
            if($display_dp === 0.0) {
                $status = "paid";
            }

            $data = [
                'contact_id' => $customer_id,
                'account_id' => $head_account_id,
                'currency_id' => $currency_id,
                'date_recieve' => $date_recieve,
                'number' => $number,
                'description' => $description,
                'job_order_id' => $job_order_id,
                'source' => $job_order_source,
                'note' => $note_recieve,
                'additional_cost' => $additional_cost,
                'status' => $status,
            ];

            $diskon_penjualan_id = MasterAccount::where('account_type_id', 16)->first();
            if(!$diskon_penjualan_id) {
                return response()->json(['errors' => ['diskon_penjualan' => ['Please add the account of Sales Discount']]], 422);
            }
            $diskon_penjualan_id = $diskon_penjualan_id->id;
            $prepaid_sales_id = MasterAccount::where('account_type_id', 10)->first();
            if(!$prepaid_sales_id) {
                return response()->json(['errors' => ['pendapatan_lain' => ['Please add the account of Prepaid Sales']]], 422);
            }
            $prepaid_sales_id = $prepaid_sales_id->id;

            $receiveDetails = RecieveDetail::where('head_id', $id)->get();
            foreach($receiveDetails as $r) {
                if($r->invoice_id) {
                    $invoice_id = $r->invoice_id;
                    InvoiceHead::find($invoice_id)->update([
                        "status" => "open"
                    ]);

                    $receives = RecieveHead::whereHas('details', function($query) use ($invoice_id) {
                        $query->where('invoice_id', $invoice_id);
                    })->get();

                    foreach ($receives as $head) {
                        $all_not_paid = RecieveDetail::where('head_id', $head->id)
                            ->whereHas('invoice', function($query) {
                                $query->where('status', '!=', 'paid');
                            })
                            ->exists();

                        if ($all_not_paid) {
                            $head->update(['status' => 'open']);
                        }
                    }
                }
                $r->delete();
            }

            $receive = RecieveHead::find($id);
            $receive->update($data);
            $head_id = $receive->id;

            $totBalance = 0;
            $isDiscount = true;
            $allDetails = [];
            $tax_journal = [];
            $ar_journal = [];
            $account_charges = []; // Track account charges for journal entries
            $formData = json_decode($request->input('form_data'), true);
            $totalDiscount = 0;
            foreach ($formData as $idx => $data) {
                $tmpDiscount = 0;
                $invoice_id = $data["detail_invoice"] ?? null;
                $charge_type = $data["charge_type"] ?? 'invoice';
                
                // Skip if no invoice_id and not account charge
                if(!$invoice_id && $charge_type !== 'account') {
                    continue;
                }
                
                // For invoice charges, check if already processed
                if($charge_type === 'invoice' && $invoice_id && in_array($invoice_id, $allDetails)) {
                    continue;
                }
                $remark = $data['detail_remark'];
                $discount_type = $data['detail_discount_type'];
                $discount_nominal = $this->numberToDatabase($data['detail_discount_nominal']);
                $account_id_detail = $data['account_id'];

                $amount = $this->numberToDatabase($data['detail_jumlah']);
                $other_currency = $data['other_currency'];
                $currency_via_id = null;
                $amount_via = null;
                if($other_currency === "1") {
                    $currency_via_id = $data['other_currency_type'];
                    if($currency_via_id) {
                        $amount = $this->numberToDatabase($data['other_currency_nominal']);
                        $exchange = ExchangeRate::find($currency_via_id);
                        $pembagi = $exchange->to_nominal/$exchange->from_nominal;
                        if($exchange->from_currency_id === $receive->currency_id) {
                            $pembagi = $exchange->from_nominal/$exchange->to_nominal;
                        }
                        $amount_via = $amount;
                        $amount = $amount*$pembagi;
                    } else {
                        $currency_via_id = null;
                    }
                }

                $totBalance += $amount;

                $is_dp = $data["dp_desc"];
                $dp_type = null;
                $dp_nominal = null;
                if($idx > 0) {
                    if($is_dp == 1 && $isDiscount === true) {
                        continue;
                    } else if($is_dp == 0 && $isDiscount === false) {
                        continue;
                    }
                }
                $total_after_discount = 0;
                $totalWithPPn = 0;
                if($is_dp == 1) {
                    if($idx === 0) {
                        $isDiscount = false;
                    }
                    $dp_type = $data["detail_dp_type"];
                    $dp_nominal = $this->numberToDatabase($data["detail_dp_nominal"]);
                } else {
                    // Only process invoice logic for invoice charges
                    if($charge_type === 'invoice' && $invoice_id) {
                        $invoice = InvoiceHead::find($invoice_id);

                        $invoice->update([
                            "status" => "paid"
                        ]);

                    foreach($invoice->details as $d) {
                        $totalFull = ($d->price*$d->quantity);
                        $discTotal = 0;
                        if($d->discount_type === "persen") {
                            $discTotal = ($d->discount_nominal/100)*$totalFull;
                        }else{
                            $discTotal = $d->discount_nominal;
                        }
                        $tmpDiscount += $discTotal;
                        $totalFull -= $discTotal;
                        $discTotal = 0;

                        $pajak = 0;
                        if(!$d->tax_id) {
                            $tax_id = null;
                        }else{
                            $tax = MasterTax::find($d->tax_id);
                            $pajak += ($tax->tax_rate/100) * $totalFull;
                            $totalFull -= $pajak;
                            if($tax->tax_rate > 0 && !$tax->account_id){
                                DB::rollBack();
                                // dd($e->getMessage());
                                return response()->json(['errors' => ['error' => ['Add the account to tax if rate more than 0']]], 422);
                            }else if($tax->account_id){
                                $grand_total -= $pajak;
                                $tax_journal[] = [$pajak, 0 , $tax->account_id ];

                            }else if($tax->tax_rate == 0 && !$tax->account_id ){
                                // Skip
                            }
                        }

                        if($invoice->discount_type === "persen") {
                            $discTotal = ($invoice->discount_nominal/100)*$totalFull;
                        }else{
                            $discTotal = $invoice->discount_nominal;
                        }
                        // $totalDiscount += $discTotal;
                        $tmpDiscount += $discTotal;
                        $totalFull -= $discTotal;
                        $discTotal = 0;


                        $total_after_discount += ($totalFull - $discTotal);

                    }

                    $ppn_tax = MasterTax::find($invoice->tax_id);
                    $discTotal = 0;
                    if ($ppn_tax && $ppn_tax->account_id) {
                        $ppn_amount = $total_after_discount * ($ppn_tax->tax_rate / 100);
                        if($discount_type === "persen") {
                            $discTotal = ($discount_nominal/100)*($total_after_discount + $ppn_amount);
                            $ar_journal[] = [0,($total_after_discount + $ppn_amount) - (($discTotal * 2) )  ,$account_id_detail];
                        }else{
                            $discTotal = $discount_nominal;
                            $ar_journal[] = [0,($total_after_discount + $ppn_amount) - (($discTotal * 2) )  ,$account_id_detail];
                        }
                        $totalDiscount += $discTotal;
                        // DB::rollBack();
                        // dd($ar_journal ,($discTotal + $tmpDiscount) , $totalDiscount,($total_after_discount + $ppn_amount));
                        // $totalFull -= $discTotal;
                        $grand_total -= $ppn_amount;
                        $tax_journal[] = [$ppn_amount, 0, $ppn_tax->account_id, $ppn_tax->id];
                    }else{
                        if($discount_type === "persen") {
                            $discTotal = ($discount_nominal/100)*$total_after_discount;
                            $ar_journal[] = [0,$total_after_discount  - (($discTotal * 2) )  ,$account_id_detail];
                        }else{
                            $discTotal = $discount_nominal;
                            $ar_journal[] = [0,$total_after_discount  - (($discTotal * 2) )  ,$account_id_detail];
                        }
                        $totalDiscount += $discTotal;
                    }

                    Sao::where('invoice_id', $invoice_id)->update([
                        'isPaid' => true,
                    ]);

                    $receives = RecieveHead::whereHas('details', function($query) use ($invoice_id) {
                        $query->where('invoice_id', $invoice_id);
                    })->get();

                    foreach ($receives as $head) {
                        $all_paid = RecieveDetail::where('head_id', $head->id)
                            ->whereHas('invoice', function($query) {
                                $query->where('status', '!=', 'paid');
                            })
                            ->doesntExist();

                        if ($all_paid) {
                            $head->update(['status' => 'paid']);
                        } else {
                            $head->update(['status' => 'open']);
                        }
                    }
                    } // End of invoice processing
                }

                $detail = [
                    'head_id' => $head_id,
                    'invoice_id' => $charge_type === 'invoice' ? $invoice_id : null,
                    'discount_type' => $discount_type,
                    'discount_nominal' => $discount_nominal,
                    'dp_type' => $dp_type,
                    'dp_nominal' => $dp_nominal,
                    'currency_via_id' => $currency_via_id,
                    'amount_via' => $amount_via,
                    'remark' => $remark,
                    'account_id' => $account_id_detail,
                    'charge_type' => $charge_type,
                    'amount' => $charge_type === 'account' ? $amount : null,
                    'description' => $charge_type === 'account' ? ($data['description'] ?? null) : null,
                ];

                // Only add to allDetails if it's an invoice charge
                if($charge_type === 'invoice' && $invoice_id) {
                    $allDetails[] = $invoice_id;
                }

                RecieveDetail::create($detail);
                
                // Collect account charges for journal entries
                if($charge_type === 'account') {
                    $account_charges[] = [
                        'amount' => $amount,
                        'account_id' => $account_id_detail,
                        'description' => $data['description'] ?? null
                    ];
                }
            }

            if($isDiscount === true) {
                $receive->update([ "status" => "paid" ]);
            }
            // $totalDiscount += $discount_value;
            BalanceAccount::where('transaction_id', $id)->where('transaction_type_id', 4)->forceDelete();
            if($display_dp > 0 && $isDiscount === false) {
                $flow = [
                    //debit, kredit
                    [$grand_total, 0, $head_account_id],
                    [0, $display_dp, $prepaid_sales_id]
                ];
            } else {
                $flow = [
                    //debit, kredit
                    [$grand_total , 0, $head_account_id],
                    [$display_dp_invoice, 0, $prepaid_sales_id],
                    [0, $totalDiscount, $diskon_penjualan_id],
                    // [0, $additional_cost, $pendapatan_lain_id],
                    // [0, $totBalance+$display_dp_invoice, $piutang_usaha_id]
                ];
            }
            
            // Add account charges to flow
            $account_charge_flow = [];
            foreach($account_charges as $charge) {
                $account_charge_flow[] = [
                    0, // debit
                    $charge['amount'], // credit
                    $charge['account_id'] // account_id
                ];
            }
            
            $flow = [
                ...$flow,
                ...$ar_journal,
                ...$tax_journal,
                ...$account_charge_flow,
            ];

            foreach ($flow as $item) {
                $cashflowData = [
                    'transaction_id' => $head_id,
                    'master_account_id' => $item[2],
                    'transaction_type_id' => 4,
                    "date" => $date_recieve,
                    'currency_id' => $currency_id,
                    'debit' => $item[0],
                    'credit' => $item[1]
                ];
                BalanceAccount::create($cashflowData);
            }
            DB::commit();

            return response()->json(['message' => 'update successfully!'], 200);
        } catch (Exception $e) {
            //throw $th;
            DB::rollBack();
            // dd($e->getMessage());
            return response()->json(['error' => 'Error On App Please Contact IT Support'], 500);
        }
    }

    public function getJurnal($id)
    {
        $data = RecieveHead::find($id);
        return view('financepiutang::receive-payment.jurnal', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $recieveHead = RecieveHead::findOrFail($id);
        if($this->hasLatestReceiveDetail($recieveHead)) {
            return redirect()->back()->withErrors(['error' => 'Please delete latest receive payment first'])->withInput();
        }

        BalanceAccount::where('transaction_id', $id)->where('transaction_type_id', 4)->delete();
        $receive = RecieveDetail::where('head_id', $id)->get();
        foreach($receive as $re) {
            $invoice_id = $re->invoice_id;
            InvoiceHead::find($invoice_id)->update([
                "status" => "open"
            ]);

            $receives = RecieveHead::whereHas('details', function($query) use ($invoice_id) {
                $query->where('invoice_id', $invoice_id);
            })->get();

            foreach ($receives as $head) {
                $all_not_paid = RecieveDetail::where('head_id', $head->id)
                    ->whereHas('invoice', function($query) {
                        $query->where('status', '!=', 'paid');
                    })
                    ->exists();

                if ($all_not_paid) {
                    $head->update(['status' => 'open']);
                }
            }

            $re->delete();
        }

        $recieveHead->delete();

        toast('Data Deleted Successfully!', 'success');
        return redirect()->back()->with('success', 'delete successfully!');
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }

    private function hasLatestReceiveDetail($head) {
        $current = RecieveDetail::where('head_id', $head->id)->get();
        foreach ($current as $detail) {
            $latestHead = RecieveHead::whereHas('details', function($query) use ($detail) {
                $query->where('invoice_id', $detail->invoice_id);
            })
            ->where('number', '>', $head->number)
            ->pluck('id');

            $latest = RecieveDetail::whereIn('head_id', $latestHead)
                        ->where('invoice_id', $detail->invoice_id)
                        ->exists();

            if ($latest) {
                return true;
            }
        }

        return false;
    }
}
