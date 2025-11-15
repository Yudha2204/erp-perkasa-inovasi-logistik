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
use Illuminate\Validation\Rule;

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
        $head = OrderHead::orderBy('date_order','ASC')->get();
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

        $prefix = 'PO-' . date('Ym');
        $latest_order_for_month = OrderHead::where('transaction', 'like', $prefix . '-%')->orderBy('id', 'desc')->first();
        $new_order_number = 1;
        if ($latest_order_for_month) {
            $parts = explode('-', $latest_order_for_month->transaction);
            $last_number = end($parts);
            if (is_numeric($last_number)) {
                $new_order_number = (int)$last_number + 1;
            }
        }
        $transaction_number = $prefix . '-' . str_pad($new_order_number, 4, '0', STR_PAD_LEFT);

        return view('financepayments::purchase-order.create', compact('vendor', 'currencies', 'terms', 'taxs', 'ppn_tax', 'customer', 'transaction_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
                'no_transaction'    => ['required', Rule::unique('account_payable_head', 'transaction')->whereNull('deleted_at')],
                'date_order' => 'required',
                'currency_id' => 'required'
            ], [
                "vendor_id.required" => "The vendor field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_order.required" => "The date is required.",
                "currency_id.required" => "The currency is required.",
                // "no_transaction.unique" => 'The transaction format already use.'
            ]);
            // dd($request->all());
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $customer_id = $request->input('customer_id');
            if($customer_id === "null") {
                $customer_id = null;
            }
            $vendor_id = $request->input('vendor_id');
            if($customer_id == $vendor_id) {
                DB::rollBack();
                return response()->json(["errors" => ["errors" => "Please input different customer"]], 422);
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
                        return response()->json(['errors' => ['no_referensi' => 'Please input a valid no referensi']], 422);
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
            $diskon_pembelian_id = MasterAccount::where('account_type_id', 24)->where('type', 'detail')->first();
            if(!$diskon_pembelian_id) {
                DB::rollBack();
                return response()->json(['errors' => ['diskon_pembelian' => 'Please add the account of Purchase Discount']], 422);
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
            $calculated_subtotal_after_item_discounts = 0;
            $calculated_total_item_discount = 0;

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
                $item_base_total = ($price_detail*$qty_detail);
                $item_discount_amount = 0;
                if($disc_type_detail === "persen") {
                    $item_discount_amount = ($discount_detail/100)*$item_base_total;
                }else{
                    $item_discount_amount = $discount_detail;
                }
                $calculated_total_item_discount += $item_discount_amount;
                $item_total_after_discount = $item_base_total - $item_discount_amount;
                $calculated_subtotal_after_item_discounts += $item_total_after_discount;

                $pajak = 0;
                if($tax_id) {
                    $tax = MasterTax::find($tax_id);

                    $pajak = (($tax->tax_rate/100) * $item_total_after_discount);
                    if($tax->tax_rate > 0 && !$tax->account_id){
                        DB::rollBack();
                        return response()->json(['errors' => ['error' => 'Add the account to tax if rate more than 0']], 422);
                    }else if($tax->account_id){
                        // $tax_journal[] = [$pajak, 0, $tax->account_id];
                    }
                }else{
                    $tax_id == null;
                }

                $expense_journal[] = [$item_base_total, 0, $expense_acc_id];

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

            // Recalculate overall discount value
            $overall_discount_value_recalculated = 0;
            if ($discount_type === 'persen') {
                $overall_discount_value_recalculated = $calculated_subtotal_after_item_discounts * ($discount_nominal / 100);
            } else if($discount_nominal) {
                $overall_discount_value_recalculated = $discount_nominal;
            }

            $recalculated_total_discount = $calculated_total_item_discount + $overall_discount_value_recalculated;
            $total_after_all_discounts_recalculated = $calculated_subtotal_after_item_discounts - $overall_discount_value_recalculated;

            // Recalculate PPN amount
            $recalculated_ppn_amount = 0;
            $ppn_tax = MasterTax::find($ppn_tax_id);
            if ($ppn_tax && $ppn_tax->account_id) {
                $recalculated_ppn_amount = $total_after_all_discounts_recalculated * ($ppn_tax->tax_rate / 100);
                $tax_journal[] = [$recalculated_ppn_amount, 0, $ppn_tax->account_id];
            }

            // Final grand total
            $recalculated_grand_total = $total_after_all_discounts_recalculated + $recalculated_ppn_amount;

            $flow = [
                //debit, kredit
                [0, $recalculated_grand_total, $coa_ap],
                [0, $recalculated_total_discount, $diskon_pembelian_id],
            ];
            $flow = [
                ...$flow,
                ...$expense_journal,
                ...$tax_journal,
            ];
            // DB::rollBack();
            // dd($flow);
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
                "total" => $recalculated_grand_total,
                'already_paid' => 0,
                "remaining" => $recalculated_grand_total,
                "isPaid" => false,
                "type" => "vendor",
            ];
            Sao::create($dataSao);

            DB::commit();
            return response()->json(['message' => 'create successfully!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['error' => 'Error On App Please Contact IT Support'], 500);
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

        $coa_ap = MasterAccount::where('account_type_id', 8)->where('master_currency_id', $order->currency_id)->get();
        $coa_expense = MasterAccount::whereIn('account_type_id', [17, 18])->get();

        return view('financepayments::purchase-order.edit', compact('vendor', 'customer', 'terms', 'taxs', 'ppn_tax','order', 'job_order', 'currencies', 'coa_ap', 'coa_expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
                'no_transaction'    => ['required', Rule::unique('account_payable_head', 'transaction')->ignore($id)->whereNull('deleted_at')],
                'date_order' => 'required',
                'currency_id' => 'required'
            ], [
                "vendor_id.required" => "The vendor field is required.",
                "no_transaction.required" => "The transaction number is required.",
                "date_order.required" => "The date is required.",
                "currency_id.required" => "The currency is required.",
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $customer_id = $request->input('customer_id');
            if($customer_id === "null") {
                $customer_id = null;
            }
            $vendor_id = $request->input('vendor_id');
            if($customer_id == $vendor_id) {
                DB::rollBack();
                return response()->json(["errors" => ["errors" => "Please input different customer"]], 422);
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
                        return response()->json(['errors' => ['no_referensi' => 'Please input a valid no referensi']], 422);
                    }
                    $operation_id = $exp_operation[0];
                    $operation_source = $exp_operation[1];
                }
                $transit = $request->input('transit_via');
            }

            $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
            $discount_type = $request->input('discount_type');
            $discount_nominal = $this->numberToDatabase($request->input('discount'));

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

            $order = OrderHead::find($id);
            $order->update($data);
            $head_id = $order->id;

            if($transit) {
                if($order->transit_via && $order->transit_via !== $transit) {
                    if($order->source === "export") {
                        VendorOperationExport::find($order->transit_via)->update(["vendor" => null]);
                        VendorOperationExport::find($transit)->update(["vendor" => $head_id]);
                    } else if($order->source === "import") {
                        VendorOperationImport::find($order->transit_via)->update(["vendor" => null]);
                        VendorOperationImport::find($transit)->update(["vendor" => $head_id]);
                    }
                }
            }

            $formData = json_decode($request->input('form_data'), true);
            foreach ($formData as $data) {
                $operator = explode(':', $data['operator']);
                $detail_id = $operator[0];
                $action = $operator[1];

                if ($action === 'delete' && $detail_id != '0') {
                    OrderDetail::find($detail_id)->delete();
                    continue;
                }

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

                $detailData = [
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
                ];

                if ($action === 'create') {
                    OrderDetail::create($detailData);
                } else if ($action === 'update') {
                    OrderDetail::find($detail_id)->update($detailData);
                }
            }

            BalanceAccount::where('transaction_id', $head_id)->where('transaction_type_id', 7)->forceDelete();

            // Re-fetch the order with updated details to ensure correct calculations
            $order = OrderHead::with('details')->find($id);

            $tax_journal = [];
            $expense_journal = [];
            $calculated_subtotal_after_item_discounts = 0;
            $calculated_total_item_discount = 0;
            $diskon_pembelian_id = MasterAccount::where('account_type_id', 24)->where('type', 'detail')->first();
            if(!$diskon_pembelian_id) {
                DB::rollBack();
                return response()->json(['errors' => ['diskon_pembelian' => 'Please add the account of Purchase Discount']], 422);
            }
            $diskon_pembelian_id = $diskon_pembelian_id->id;

            foreach($order->details as $d) {
                $item_base_total = ($d->price*$d->quantity);
                $item_discount_amount = 0;
                if($d->discount_type === "persen") {
                    $item_discount_amount = ($d->discount_nominal/100)*$item_base_total;
                }else{
                    $item_discount_amount = $d->discount_nominal;
                }
                $calculated_total_item_discount += $item_discount_amount;
                $item_total_after_discount = $item_base_total - $item_discount_amount;
                $calculated_subtotal_after_item_discounts += $item_total_after_discount;

                $pajak = 0;
                if($d->tax_id) {
                    $tax = MasterTax::find($d->tax_id);
                    if($tax) {
                        $pajak = (($tax->tax_rate/100) * $item_total_after_discount);
                        if($tax->tax_rate > 0 && !$tax->account_id){
                            DB::rollBack();
                            return response()->json(['errors' => ['error' => 'Add the account to tax if rate more than 0']], 422);
                        } else if ($tax->account_id) {
                            // $tax_journal[] = [$pajak, 0, $tax->account_id];
                        }
                    }
                }
                $expense_journal[] = [$item_base_total, 0, $d->account_id];
            }

            // Recalculate overall discount value
            $overall_discount_value_recalculated = 0;
            if ($discount_type === 'persen') {
                $overall_discount_value_recalculated = $calculated_subtotal_after_item_discounts * ($discount_nominal / 100);
            } else if($discount_nominal) {
                $overall_discount_value_recalculated = $discount_nominal;
            }

            $recalculated_total_discount = $calculated_total_item_discount + $overall_discount_value_recalculated;
            $total_after_all_discounts_recalculated = $calculated_subtotal_after_item_discounts - $overall_discount_value_recalculated;

            // Recalculate PPN amount
            $recalculated_ppn_amount = 0;
            $ppn_tax = MasterTax::find($ppn_tax_id);
            if ($ppn_tax && $ppn_tax->account_id) {
                $recalculated_ppn_amount = $total_after_all_discounts_recalculated * ($ppn_tax->tax_rate / 100);
                $tax_journal[] = [$recalculated_ppn_amount, 0, $ppn_tax->account_id];
            }

            // Final grand total
            $recalculated_grand_total = $total_after_all_discounts_recalculated + $recalculated_ppn_amount;

            $flow = [
                //debit, kredit
                [0, $recalculated_grand_total, $coa_ap],
                [0, $recalculated_total_discount, $diskon_pembelian_id],
            ];
            $flow = [
                ...$flow,
                ...$expense_journal,
                ...$tax_journal,
            ];

            foreach ($flow as $item) {
                BalanceAccount::create([
                    'transaction_id' => $head_id,
                    'master_account_id' => $item[2],
                    'transaction_type_id' => 7,
                    "date" => $date_order,
                    'debit' => $item[0],
                    'credit' => $item[1],
                    'currency_id' => $currency_id,
                ]);
            }

            Sao::where('order_id', $id)->update([
                "vendor_id" => $vendor_id,
                "contact_id" => $customer_id,
                "currency_id" => $currency_id,
                "date" => $date_order,
                "total" => $recalculated_grand_total,
                "remaining" => $recalculated_grand_total,
            ]);

            DB::commit();
            return response()->json(['message' => 'update successfully!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error On App Please Contact IT Support'], 500);
        }
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

        return view('financepayments::purchase-order.jurnal', [
            'jurnal' => $jurnal
            // 'title' => 'Journal Account Payable',
            // 'transactionNumber' => $jurnal->transaction,
            // 'transactionDate' => $jurnal->date_order,
            // 'description' => $jurnal->description,
            // 'jurnals' => $jurnal->jurnal,
            // 'currency' => $jurnal->currency->initial,
            // 'backUrl' => route('finance.payments.account-payable.index'),
            // 'jurnalsIDR' => $jurnal->jurnalIDR,
        ]);
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
