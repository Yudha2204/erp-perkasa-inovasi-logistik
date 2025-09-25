<?php

namespace Modules\FinancePayments\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTax;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinancePayments\App\Models\OrderDetail;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePayments\App\Models\PaymentDetail;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Modules\Operation\App\Models\VendorOperationExport;
use Modules\Operation\App\Models\VendorOperationImport;
use Modules\ReportFinance\App\Models\Sao;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-account_payable@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-account_payable@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-account_payable@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-account_payable@finance', ['only' => ['destroy']]);
    }

    public function index()
    {
        $head = OrderHead::all();
        return view('financepayments::purchase-order.index', compact('head'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendor = MasterContact::whereJsonContains('type','2')
            ->get();
        $customer = MasterContact::whereJsonContains('type','1')
            ->get();
        $currencies = MasterCurrency::all();
        $terms = MasterTermOfPayment::all();
        $taxs = MasterTax::where('status',1)->get();
        // filter hanya yang tax_type = 'ppn'
        $ppn_tax = $taxs->where('type', 'PPN');
        $taxs = $taxs->where('type', 'PPH');
        return view('financepayments::purchase-order.create', compact('vendor', 'currencies', 'terms', 'taxs', 'ppn_tax', 'customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
                'no_transaction'    => 'required|unique:account_payable_head,transaction',
                'date_order' => 'required',
                'currency_id' => 'required'
            ], [
                "vendor_id.required" => "The vendor field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_order.required" => "The date is required.",
                "currency_id.required" => "The currency is required.",
                "no_transaction.unique" => 'The transaction format already use.'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                toast('Failed to Add Data!','error');
                return redirect()->back()
                            ->withErrors($validator);
            }

            $customer_id = $request->input('customer_id');
            if($customer_id === "null") {
                $customer_id = null;
            }
            $vendor_id = $request->input('vendor_id');
            if($customer_id == $vendor_id) {
                DB::rollBack();
                toast('Failed to Add Data!','error');
                return redirect()->back()
                            ->withErrors(["errors" => "Please input different customer"]);
            }
            $transaction = $request->input('no_transaction');
            $date_order = $request->input('date_order');
            $currency_id = $request->input('currency_id');
            $description = $request->input('des_head_order');
            $coa_ap = $request->input('coa_ap');
            $ppn_tax_id = $request->input('ppn_tax') ? explode(":",$request->input('ppn_tax'))[0] : null;

            $operation_id = null;
            $operation_source = null;
            $transit = null;
            $choose = $request->input('choose_job_order');
            if($choose === "1") {
                $operation = $request->input('job_order_id');
                if($operation) {
                    $exp_operation = explode(":", $operation);
                    if(sizeof($exp_operation) !== 2) {
                        DB::rollBack();
                        return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi']);
                    }
                    $operation_id = $exp_operation[0];
                    $operation_source = $exp_operation[1];
                }
                $transit = $request->input('transit_via');
            }

            $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
            $discount_type = $request->input('discount_type');
            $discount_nominal = $this->numberToDatabase($request->input('discount'));

            $total_display = $this->numberToDatabase($request->input('total_display'));
            $display_pajak = $this->numberToDatabase($request->input('display_pajak'));
            $display_dp = $this->numberToDatabase($request->input('display_dp'));
            $discount_display = $this->numberToDatabase($request->input('discount_display'));

            $data = [
                'customer_id' => $customer_id,
                'vendor_id' => $vendor_id,
                'currency_id' => $currency_id,
                'operation_id' => $operation_id,
                'source' => $operation_source,
                'transit_via' => $transit,
                'transaction' => $transaction,
                'date_order' => $date_order,
                'description' => $description,
                'additional_cost' => $additional_cost,
                'discount_type' => $discount_type,
                'discount_nominal' => $discount_nominal,
                'account_id' => $coa_ap,
                'tax_id' => $ppn_tax_id,
                'status' => "open",
            ];
            // DB::rollBack();
            // dd($data);
            $diskon_pembelian_id = MasterAccount::where('account_type_id', 16)->first();
            if(!$diskon_pembelian_id) {
                DB::rollBack();
                return redirect()->back()->withErrors(['diskon_pembelian' => 'Please add the account of Sales Discount']);
            }
            $diskon_pembelian_id = $diskon_pembelian_id->id;

            $order = OrderHead::create($data);
            $head_id = $order->id;

            if($transit) {
                if($operation_source === "export") {
                    VendorOperationExport::find($transit)->update([
                        "vendor" => $head_id
                    ]);
                } else if($operation_source === "import") {
                    VendorOperationImport::find($transit)->update([
                        "vendor" => $head_id
                    ]);
                }
            }

            $formData = json_decode($request->input('form_data'), true);
            $tax_journal = [];
            $expense_journal = [];
            $totalDetailWithoutDiscount = 0;
            foreach ($formData as $data) {
                $des_detail = $data['des_detail'];
                $renark_detail = $data['remark_detail'];
                $qty_detail = $this->numberToDatabase($data['qty_detail']);
                $uom_detail = $data['uom_detail'];
                $pajak_detail = $data['pajak_detail'];
                $price_detail = $this->numberToDatabase($data['price_detail']);
                $discount_detail = $this->numberToDatabase($data['disc_detail']);
                $disc_type_detail = $data['disc_type_detail'];
                $expense_acc_id = $data['coa_expense_detail'];

                $exp_tax = explode(":", $pajak_detail);
                $tax_id = $exp_tax[0];
                $totalFull = ($price_detail*$qty_detail);
                if($disc_type_detail === "persen") {
                    $discTotal = ($discount_detail/100)*$totalFull;
                }else{
                    $discTotal = $discount_detail;
                }
                $totalFull -= $discTotal;
                $pajak = 0;

                if($tax_id) {
                    $tax = MasterTax::find($tax_id);

                    $pajak = (($tax->tax_rate/100) * $totalFull);
                    $totalFull -= $pajak;
                    if($tax->tax_rate > 0 && !$tax->account_id){
                        DB::rollBack();
                        return redirect()->back()
                                ->withErrors(['error' => 'Add the account to tax if rate more than 0']);
                    }else if($tax->account_id){
                        $tax_journal[] = [$pajak, 0 , $tax->account_id];
                    }
                }
                $totalDetailWithoutDiscount += $totalFull;

                $expense_journal[] = [(($totalFull - $pajak) - ($totalDetailWithoutDiscount * ($discount_nominal/100))), 0, $expense_acc_id];

                OrderDetail::create([
                    'head_id' => $head_id,
                    'description' => $des_detail,
                    'quantity' => $qty_detail,
                    'uom' => $uom_detail,
                    'price' => $price_detail,
                    'tax_id' => $tax_id,
                    'remark' => $renark_detail,
                    'discount_type' => $disc_type_detail,
                    'discount_nominal' => $discount_detail,
                    'account_id' => $expense_acc_id
                ]);
            }

            $ppn_tax = MasterTax::find($ppn_tax_id);
            if ($ppn_tax && $ppn_tax->account_id) {
                $total_after_discount = $total_display;
                $ppn_amount = $total_after_discount - ($total_after_discount /(1 + ($ppn_tax->tax_rate / 100)));
                $tax_journal[] = [$ppn_amount, 0, $ppn_tax->account_id];
            }


            $flow = [
                //debit, kredit
                [0, $total_display, $coa_ap],
                [0, ($totalDetailWithoutDiscount * ($discount_nominal/100)), $diskon_pembelian_id],
            ];
            $flow = [
                    ...$flow,
                    ...$expense_journal,
                    ...$tax_journal,
            ];

            foreach ($flow as $item) {
                $cashflowData = [
                    'transaction_id' => $head_id,
                    'master_account_id' => $item[2],
                    'transaction_type_id' => 7,
                    "date" => $date_order,
                    'debit' => $item[0],
                    'credit' => $item[1],
                    'currency_id' => $currency_id,

                ];
                BalanceAccount::create($cashflowData);
            }

            $dataSao = [
                "order_id" => $head_id,
                "vendor_id" => $vendor_id,
                "contact_id" => $customer_id,
                "currency_id" => $currency_id,
                "date" => $date_order,
                "account" => "hutang",
                "total" => $total_display,
                'already_paid' => 0,
                "remaining" => $total_display,
                "isPaid" => false,
                "type" => "vendor",
            ];
            Sao::create($dataSao);

            DB::commit();
            toast('Data Created Successfully!', 'success');
            return redirect()->route('finance.payments.account-payable.index')->with('success', 'create successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            toast('Failed to Add Data!','error');
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $order = OrderHead::find($id);
        return view('financepayments::purchase-order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payment = PaymentDetail::where('payable_id', $id)->first();
        if($payment) {
            toast('Failed to Update Data!', 'error');
            return redirect()->route('finance.payments.account-payable.index')->withErrors(["error" => "There is payment link to this account payable"]);
        }
        $order = OrderHead::find($id);
        $vendor = MasterContact::whereJsonContains('type','2')
            ->get();
        $customer = MasterContact::whereJsonContains('type','1')
            ->get();
        $currencies = MasterCurrency::all();
        $terms = MasterTermOfPayment::all();
        $taxs = MasterTax::where('status',1)->get();
        // filter hanya yang tax_type = 'ppn'
        $ppn_tax = $taxs->where('type', 'PPN');
        $taxs = $taxs->where('type', 'PPH');
        $customer_id = $order->customer_id;
        $export = OperationExport::with('marketing')
            ->whereHas('marketing', function ($query) use ($customer_id) {
                $query->where('contact_id', $customer_id);
            })->get();
        $import = OperationImport::with('marketing')
            ->whereHas('marketing', function ($query) use ($customer_id) {
                $query->where('contact_id', $customer_id);
            })->get();
        $job_order = $export->concat($import);

        return view('financepayments::purchase-order.edit', compact('vendor', 'customer', 'terms', 'taxs', 'order', 'job_order', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'no_transaction'    => 'required|unique:account_payable_head,transaction,' . $id,
            'date_order' => 'required',
            'currency_id' => 'required'
        ], [
            "vendor_id.required" => "The vendor field is required.",
            "no_transaction.required" => "The transaction number is required.",
            "date_order.required" => "The date is required.",
            "currency_id.required" => "The currency is required.",
            "no_transaction.unique" => 'The transaction format already use.'
        ]);

        if ($validator->fails()) {
            toast('Failed to Update Data!','error');
            return redirect()->back()
                        ->withErrors($validator);
        }

        $customer_id = $request->input('customer_id');
        if($customer_id === "null") {
            $customer_id = null;
        }
        $vendor_id = $request->input('vendor_id');
        if($customer_id == $vendor_id) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["errors" => "Please input different customer"]);
        }
        $transaction = $request->input('no_transaction');
        $date_order = $request->input('date_order');
        $currency_id = $request->input('currency_id');
        $description = $request->input('des_head_order');

        $operation_id = null;
        $operation_source = null;
        $transit = null;
        $choose = $request->input('choose_job_order');
        if($choose === "1") {
            $operation = $request->input('job_order_id');
            if($operation) {
                $exp_operation = explode(":", $operation);
                if(sizeof($exp_operation) !== 2) {
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi']);
                }
                $operation_id = $exp_operation[0];
                $operation_source = $exp_operation[1];
            }
            $transit = $request->input('transit_via');
        }

        $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
        $discount_type = $request->input('discount_type');
        $discount_nominal = $this->numberToDatabase($request->input('discount'));

        $total_display = $this->numberToDatabase($request->input('total_display'));
        $display_pajak = $this->numberToDatabase($request->input('display_pajak'));
        $display_dp = $this->numberToDatabase($request->input('display_dp'));
        $discount_display = $this->numberToDatabase($request->input('discount_display'));

        $data = [
            'customer_id' => $customer_id,
            'vendor_id' => $vendor_id,
            'currency_id' => $currency_id,
            'operation_id' => $operation_id,
            'source' => $operation_source,
            'transit_via' => $transit,
            'transaction' => $transaction,
            'date_order' => $date_order,
            'description' => $description,
            'additional_cost' => $additional_cost,
            'discount_type' => $discount_type,
            'discount_nominal' => $discount_nominal,
            'status' => "open",
        ];

        $order = OrderHead::find($id);
        if($transit) {
            if($order->transit_via) {
                if($order->transit_via !== $transit) {
                    if($order->source === "export") {
                        VendorOperationExport::find($order->transit_via)->update([
                            "vendor" => null
                        ]);
                        VendorOperationExport::find($transit)->update([
                            "vendor" => $id
                        ]);
                    } else if($order->source === "import") {
                        VendorOperationImport::find($order->transit_via)->update([
                            "vendor" => null
                        ]);
                        VendorOperationImport::find($transit)->update([
                            "vendor" => $id
                        ]);
                    }
                }
            } else {
                if($operation_source === "export") {
                    VendorOperationExport::find($transit)->update([
                        "vendor" => $id
                    ]);
                } else if($operation_source === "import") {
                    VendorOperationImport::find($transit)->update([
                        "vendor" => $id
                    ]);
                }
            }
        } else {
            if($order->transit_via) {
                if($order->source === "export") {
                    VendorOperationExport::find($order->transit_via)->update([
                        "vendor" => null
                    ]);
                } else if($order->source === "import") {
                    VendorOperationImport::find($order->transit_via)->update([
                        "vendor" => null
                    ]);
                }
            }
        }

        $order->update($data);
        $head_id = $order->id;

        $totBalance = 0;
        $formData = json_decode($request->input('form_data'), true);
        $grand_dp = 0;
        foreach ($formData as $data) {
            $des_detail = $data['des_detail'];
            $renark_detail = $data['remark_detail'];
            $qty_detail = $this->numberToDatabase($data['qty_detail']);
            $uom_detail = $data['uom_detail'];
            $pajak_detail = $data['pajak_detail'];
            $price_detail = $this->numberToDatabase($data['price_detail']);
            $discount_detail = $this->numberToDatabase($data['disc_detail']);
            $disc_type_detail = $data['disc_type_detail'];

            $exp_tax = explode(":", $pajak_detail);
            $tax_id = $exp_tax[0];
            if(!$tax_id) {
                $tax_id = null;
            }

            $is_dp = $data['dp_desc'];
            $dp_type_detail = null;
            $dp_detail = null;
            if($is_dp == 1) {
                $dp_type_detail = $data['dp_type_detail'];
                $dp_detail = $this->numberToDatabase($data['dp_detail']);
            }

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
            $totalFull -= $discTotal;
            $pajak = 0;
            if($tax_id == 2 ) {
                $pajak += (10/100)*$totalFull;
            } else if($tax_id == 3 ) {
                $pajak += (5/100)*$totalFull;
            }
            $totalFull -= $pajak;
            $dp = 0;
            if($dp_type_detail == "persen") {
                $dp += ($dp_detail/100)*$totalFull;
            } else {
                $dp += $dp_detail;
            }
            $grand_dp += $dp;
            $totalFull = ($price_detail*$qty_detail);
            $discTotal = 0;
            if($disc_type_detail === "persen") {
                $discTotal = ($discount_detail/100)*$totalFull;
            }else{
                $discTotal = $discount_detail;
            }
            $totalFull -= $discTotal;
            $pajak = 0;
            if($tax_id == 2 ) {
                $pajak += (10/100)*$totalFull;
            } else if($tax_id == 3 ) {
                $pajak += (5/100)*$totalFull;
            }
            $totalFull -= $pajak;
            $dp = 0;
            if($dp_type_detail == "persen") {
                $dp += ($dp_detail/100)*$totalFull;
            } else {
                $dp += $dp_detail;
            }
            $grand_dp += $dp;

            if($exp_operator[1] === "create") {
                OrderDetail::create([
                    'head_id' => $head_id,
                    'description' => $des_detail,
                    'quantity' => $qty_detail,
                    'uom' => $uom_detail,
                    'price' => $price_detail,
                    'tax_id' => $tax_id,
                    'remark' => $renark_detail,
                    'discount_type' => $disc_type_detail,
                    'discount_nominal' => $discount_detail,
                    'dp_type' => $dp_type_detail,
                    'dp_nominal' => $dp_detail
                ]);
            } else if($exp_operator[1] === "update") {
                $orderDetail = OrderDetail::find($exp_operator[0]);
                $orderDetail->update([
                    'description' => $des_detail,
                    'quantity' => $qty_detail,
                    'uom' => $uom_detail,
                    'price' => $price_detail,
                    'tax_id' => $tax_id,
                    'remark' => $renark_detail,
                    'discount_type' => $disc_type_detail,
                    'discount_nominal' => $discount_detail,
                    'dp_type' => $dp_type_detail,
                    'dp_nominal' => $dp_detail
                ]);
            } else if($exp_operator[1] === "delete") {
                $orderDetail = OrderDetail::find($exp_operator[0]);
                if($orderDetail) {
                    $orderDetail->delete();
                }
            }
        }

        $flow = [
            //debit, kredit
            [0, $total_display],
            [0, $discount_display],
            [0, $display_pajak],
            [0, $display_dp],
            [$display_dp, 0],
            [$additional_cost, 0],
            [$totBalance, 0]
        ];

        foreach ($order->jurnal as $item) {
            if($item->master_account->code === "220100" && $item->master_account->account_name === "Hutang Usaha") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[0][0],
                    'credit' => $flow[0][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            } else if($item->master_account->code === "550100" && $item->master_account->account_name === "Diskon Pembelian") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[1][0],
                    'credit' => $flow[1][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            } else if($item->master_account->code === "110500" && $item->master_account->account_name === "PPN Masukan") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[2][0],
                    'credit' => $flow[2][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            } else if($item->master_account->code === "110001" && $item->master_account->account_name === "Kas") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[3][0],
                    'credit' => $flow[3][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            } else if($item->master_account->code === "550501" && $item->master_account->account_name === "Uang Muka kepada Vendor") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[4][0],
                    'credit' => $flow[4][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            } else if($item->master_account->code === "550500" && $item->master_account->account_name === "Biaya Produksi") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[5][0],
                    'credit' => $flow[5][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            } else if($item->master_account->code === "550000" && $item->master_account->account_name === "Beban Pokok Pendapatan") {
                $cashflowData = [
                    "date" => $date_order,
                    'debit' => $flow[6][0],
                    'credit' => $flow[6][1]
                ];
                BalanceAccount::find($item->id)->update($cashflowData);
            }
        }

        $remain = $total_display - $grand_dp;
        $dataSao = [
            "order_id" => $head_id,
            "vendor_id" => $vendor_id,
            "contact_id" => $customer_id,
            "currency_id" => $currency_id,
            "date" => $date_order,
            "account" => "hutang",
            "total" => $total_display,
            "already_paid" => $grand_dp,
            "remaining" => $remain,
            "isPaid" => false,
            "type" => "vendor",
        ];
        $tagertSao = Sao::where('order_id', $id)->first();
        $tagertSao->update($dataSao);


        return redirect()->route('finance.payments.account-payable.index')->with('success', 'create successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payment = PaymentDetail::where('payable_id', $id)->first();
        if($payment) {
            toast('Failed to Delete Data!', 'error');
            return redirect()->route('finance.payments.account-payable.index')->withErrors(["error" => "There is payment link to this account payable"]);
        }
        BalanceAccount::where('transaction_id', $id)
            ->where('transaction_type_id', 7)->delete();
        OrderDetail::where('head_id', $id)->delete();
        OrderHead::findOrFail($id)->delete();

        toast('Data Deleted Successfully!', 'success');
        return redirect()->back()->with('success', 'delete successfully!');
    }

    public function getJurnal($id)
    {
        $jurnal = OrderHead::find($id);
        return view('financepayments::purchase-order.jurnal', compact('jurnal'));
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
