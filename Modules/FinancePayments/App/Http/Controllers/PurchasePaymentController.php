<?php

namespace Modules\FinancePayments\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\ExchangeRate\App\Models\ExchangeRate;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePayments\App\Models\PaymentDetail;
use Modules\FinancePayments\App\Models\PaymentHead;
use Modules\Operation\App\Models\OperationExport;
use Modules\Operation\App\Models\OperationImport;
use Modules\ReportFinance\App\Models\Sao;

class PurchasePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-payment@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-payment@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-payment@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-payment@finance', ['only' => ['destroy']]);
    }

    public function index()
    {
        $head = PaymentHead::all();
        return view('financepayments::purchase-payment.index', compact('head'));
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
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();
        $accountTypes = AccountType::all();

        $current_year = Carbon::now()->year;
        $payment = PaymentHead::whereYear('date_payment', $current_year)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($payment) {
            $latest_number = $payment->number + 1;
        }

        return view('financepayments::purchase-payment.create', compact('vendor','customer','currencies','terms','accountTypes', 'latest_number'));
    }

    public function getTransaction(Request $request)
    {
        $date = $request->get('date');
        $payment = PaymentHead::whereYear('date_payment', Carbon::parse($date)->year)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($payment) {
            $latest_number = $payment->number + 1;
        }

        return response()->json([
            'message' => "Success",
            'data' => $latest_number
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $validator = Validator::make($request->all(), [
            'vendor_id'   => 'required',
            'account_id' => 'required',
            'date_payment'    => 'required',
            'currency_head_id' => 'required',
            'no_transactions' => 'required'
        ], [
            "vendor_id.required" => "The vendor field is required.",
            "account_id.required" => "The account name field is required.",
            "no_transaction.required" => "The transaction number is required.",
            "date_payment.required" => "The date is required.",
            "currency_head_id.required" => "The currency field is required."
        ]);

        if ($validator->fails()) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors($validator);
        }
        
        $vendor_id = $request->input('vendor_id');
        $customer_id = $request->input('customer_id');
        if($customer_id === "null") {
            $customer_id = null;
        }
        if($customer_id == $vendor_id) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["errors" => "Please input different customer"]);
        }
        $account_id = $request->input('account_id');
        $date_payment = $request->input('date_payment');
        $currency_id = $request->input('currency_head_id');

        $validator = MasterAccount::where('id', $account_id)->where('master_currency_id', $currency_id)->get()->first();
        if(!$validator) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["error" => 'Please input a valid account']);
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
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi']);
                }
                $job_order_id = $exp_job_order[0];
                $job_order_source = $exp_job_order[1];
            }
        }

        $note = $request->input('note_payment');
        $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
        $grand_total = $this->numberToDatabase($request->input('total_display'));
        $discount_display = $this->numberToDatabase($request->input('discount_display'));
        $display_dp = $this->numberToDatabase($request->input('display_dp'));
        $display_dp_order = $this->numberToDatabase($request->input('display_dp_order'));

        $status = "open";
        if($display_dp === 0.0) {
            $status = "paid";
        }

        $data = [
            'vendor_id' => $vendor_id,
            'customer_id' => $customer_id,
            'account_id' => $account_id,
            'currency_id' => $currency_id,
            'date_payment' => $date_payment,
            'number' => $number,
            'description' => $description,
            'job_order_id' => $job_order_id,
            'source' => $job_order_source,
            'note' => $note,
            'additional_cost' => $additional_cost,
            'status' => $status,
        ];

        $diskon_pembelian_id = MasterAccount::where('code', '550100')
                                ->where('account_name', 'Diskon Pembelian')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$diskon_pembelian_id) {
            return redirect()->back()->withErrors(['diskon_pembelian' => 'Please add the account of Diskon Pembelian with code number is 550100']);
        }
        $diskon_pembelian_id = $diskon_pembelian_id->id;

        $pendapatan_lain_id = MasterAccount::where('code', "440010")
                                ->where('account_name', 'Pendapatan Lain')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$pendapatan_lain_id) {
            return redirect()->back()->withErrors(['pendapatan_lain' => 'Please add the account of Pendapatan Lain with code number is 440010']);
        }
        $pendapatan_lain_id = $pendapatan_lain_id->id;

        $hutang_usaha_id = MasterAccount::where('code', "220100")
                                ->where('account_name', 'Hutang Usaha')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$hutang_usaha_id) {
            return redirect()->back()->withErrors(['hutang_usaha' => 'Please add the account of Hutang Usaha with code number is 220100']);
        }
        $hutang_usaha_id = $hutang_usaha_id->id;

        $dp_id = MasterAccount::where('code', "550501")
                                ->where('account_name', 'Uang Muka kepada Vendor')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$dp_id) {
            return redirect()->back()->withErrors(['dp' => 'Please add the account of Uang Muka kepada Vendor with code number is 550501']);
        }
        $dp_id = $dp_id->id;

        PaymentHead::create($data);
        $payment = PaymentHead::latest()->first();
        $head_id = $payment->id;

        $totBalance = 0;
        $isDiscount = true;
        $allDetails = [];
        $formData = json_decode($request->input('form_data'), true);
        foreach ($formData as $idx => $data) {
            $payable_id = $data["detail_order"];
            if(!$payable_id) {
                continue;
            }
            if(in_array($payable_id, $allDetails)) {
                continue;
            }
            $remark = $data['detail_remark'];
            $discount_type = $data['detail_discount_type'];
            $discount_nominal = $this->numberToDatabase($data['detail_discount_nominal']);

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
                    if($exchange->from_currency_id === $payment->currency_id) {
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
            
            if($is_dp == 1) {
                if($idx === 0) {
                    $isDiscount = false;
                }
                $dp_type = $data["detail_dp_type"];
                $dp_nominal = $this->numberToDatabase($data["detail_dp_nominal"]);
            } else {
                OrderHead::find($payable_id)->update([
                    "status" => "paid"
                ]);

                Sao::where('order_id', $payable_id)->update([
                    'isPaid' => true,
                ]);

                $payments = PaymentHead::whereHas('details', function($query) use ($payable_id) {
                    $query->where('payable_id', $payable_id);
                })->get();
        
                foreach ($payments as $head) {
                    $all_paid = PaymentDetail::where('head_id', $head->id)
                        ->whereHas('payable', function($query) {
                            $query->where('status', '!=', 'paid');
                        })
                        ->doesntExist();
        
                    if ($all_paid) {
                        $head->update(['status' => 'paid']);
                    } else {
                        $head->update(['status' => 'open']);
                    }
                }
            }

            $detail = [
                'head_id' => $head_id,
                'payable_id' => $payable_id,
                'discount_type' => $discount_type,
                'discount_nominal' => $discount_nominal,
                'dp_type' => $dp_type,
                'dp_nominal' => $dp_nominal,
                'currency_via_id' => $currency_via_id,
                'amount_via' => $amount_via,
                'remark' => $remark,
            ];  
            
            $allDetails[] = $payable_id;

            PaymentDetail::create($detail);
        }

        if($isDiscount === true) {
            $payment->update([ "status" => "paid" ]);
        }

        if($display_dp > 0 && $isDiscount === false) {
            $flow = [
                //debit, kredit
                [0, $display_dp, $account_id],
                [$display_dp, 0, $dp_id]
            ];
        } else {
            $flow = [
                //debit, kredit
                [0, $grand_total, $account_id],
                [0, $display_dp_order, $dp_id],
                [0, $discount_display, $diskon_pembelian_id],
                [$additional_cost, 0, $pendapatan_lain_id],
                [$totBalance+$display_dp_order, 0, $hutang_usaha_id]
            ];
        }

        foreach ($flow as $item) {
            $cashflowData = [
                'transaction_id' => $head_id,
                'master_account_id' => $item[2],
                'transaction_type_id' => 8,
                "date" => $date_payment,
                'debit' => $item[0],
                'credit' => $item[1]
            ];
            BalanceAccount::create($cashflowData);
        }

        return redirect()->route('finance.payments.purchase-payment.index')->with('success', 'create successfully!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data_payment = PaymentHead::find($id);
        return view('financepayments::purchase-payment.show', compact('data_payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $head = PaymentHead::find($id);
        if($this->hasLatestPaymentDetail($head)) {
            return redirect()->back()->withErrors(['error' => 'Please update latest payment']);
        }

        $vendors = MasterContact::whereJsonContains('type','2')
            ->get();
        $customers = MasterContact::whereJsonContains('type','1')
            ->get();
        $terms = MasterTermOfPayment::all();
        $currencies = MasterCurrency::all();
        $accounts = MasterAccount::where('master_currency_id', $head->currency_id)->get();
        $accountTypes = AccountType::all();

        $customer_id = $head->customer_id;
        $export = OperationExport::with('marketing')
        ->whereHas('marketing', function ($query) use ($customer_id) {
            $query->where('contact_id', $customer_id);
        })->get();
        $import = OperationImport::with('marketing')
            ->whereHas('marketing', function ($query) use ($customer_id) {
                $query->where('contact_id', $customer_id);
            })->get();
        $operation = $export->concat($import);
        
        $customer = $head->customer_id;
        $vendor = $head->vendor_id;
        $currency = $head->currency_id;
        $job_order = $head->job_order_id . ":" . $head->source;

        $purchaseOrder = [];
        if($currency && $vendor) {
            if($job_order && $job_order !== "null") {
                $exp_jo = explode(":", $job_order);
                $job_order_id = $exp_jo[0];
                $job_order_source = $exp_jo[1];
                $purchaseOrder = OrderHead::where('customer_id', $customer)
                            ->where('vendor_id', $vendor)
                            ->where('currency_id', $currency)
                            ->where('operation_id', $job_order_id)
                            ->where('source', $job_order_source)
                            ->where('status','!=','paid')
                            ->get();
            } else {
                $purchaseOrder = OrderHead::where('customer_id', $customer)
                    ->where('vendor_id', $vendor)
                    ->where('currency_id', $currency)
                    ->where('operation_id', null)
                    ->where('status','!=','paid')
                    ->get();
            }
        }

        $exchangeFrom = ExchangeRate::where('from_currency_id', $currency)
                    ->where('date', $head->date_payment)
                    ->with(['from_currency','to_currency'])
                    ->get();
        $exchangeTo = ExchangeRate::where('to_currency_id', $currency)
                        ->where('date', $head->date_payment)
                        ->with(['from_currency','to_currency'])
                        ->get();
        $exchange = $exchangeFrom->concat($exchangeTo);

        return view('financepayments::purchase-payment.edit', compact('vendors', 'customers', 'terms', 'currencies', 'accounts', 'accountTypes', 'head','operation','purchaseOrder','exchange'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
        $validator = Validator::make($request->all(), [
            'vendor_id'   => 'required',
            'account_id' => 'required',
            'date_payment'    => 'required',
            'currency_head_id' => 'required',
            'no_transactions' => 'required'
        ], [
            "vendor_id.required" => "The vendor field is required.",
            "account_id.required" => "The account name field is required.",
            "no_transaction.required" => "The transaction number is required.",
            "date_payment.required" => "The date is required.",
            "currency_head_id.required" => "The currency field is required."
        ]);

        if ($validator->fails()) {
            toast('Failed to Update Data!','error');
            return redirect()->back()
                        ->withErrors($validator);
        }
        
        $vendor_id = $request->input('vendor_id');
        $customer_id = $request->input('customer_id');
        if($customer_id === "null") {
            $customer_id = null;
        }
        if($customer_id == $vendor_id) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["errors" => "Please input different customer"]);
        }
        $account_id = $request->input('account_id');
        $date_payment = $request->input('date_payment');
        $currency_id = $request->input('currency_head_id');

        $validator = MasterAccount::where('id', $account_id)->where('master_currency_id', $currency_id)->get()->first();
        if(!$validator) {
            toast('Failed to Update Data!','error');
            return redirect()->back()
                        ->withErrors(["error" => 'Please input a valid account']);
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
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi']);
                }
                $job_order_id = $exp_job_order[0];
                $job_order_source = $exp_job_order[1];
            }
        }

        $note = $request->input('note_payment');
        $additional_cost = $this->numberToDatabase($request->input('additional_cost'));
        $grand_total = $this->numberToDatabase($request->input('total_display'));
        $discount_display = $this->numberToDatabase($request->input('discount_display'));
        $display_dp = $this->numberToDatabase($request->input('display_dp'));
        $display_dp_order = $this->numberToDatabase($request->input('display_dp_order'));

        $status = "open";
        if($display_dp === 0.0) {
            $status = "paid";
        }

        $data = [
            'customer_id' => $customer_id,
            'vendor_id' => $vendor_id,
            'account_id' => $account_id,
            'currency_id' => $currency_id,
            'date_payment' => $date_payment,
            'number' => $number,
            'description' => $description,
            'job_order_id' => $job_order_id,
            'source' => $job_order_source,
            'note' => $note,
            'additional_cost' => $additional_cost,
            'status' => $status,
        ];

        $diskon_pembelian_id = MasterAccount::where('code', '550100')
                                ->where('account_name', 'Diskon Pembelian')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$diskon_pembelian_id) {
            return redirect()->back()->withErrors(['diskon_pembelian' => 'Please add the account of Diskon Pembelian with code number is 550100']);
        }
        $diskon_pembelian_id = $diskon_pembelian_id->id;

        $pendapatan_lain_id = MasterAccount::where('code', "440010")
                                ->where('account_name', 'Pendapatan Lain')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$pendapatan_lain_id) {
            return redirect()->back()->withErrors(['pendapatan_lain' => 'Please add the account of Pendapatan Lain with code number is 440010']);
        }
        $pendapatan_lain_id = $pendapatan_lain_id->id;

        $hutang_usaha_id = MasterAccount::where('code', "220100")
                                ->where('account_name', 'Hutang Usaha')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$hutang_usaha_id) {
            return redirect()->back()->withErrors(['hutang_usaha' => 'Please add the account of Hutang Usaha with code number is 220100']);
        }
        $hutang_usaha_id = $hutang_usaha_id->id;

        $dp_id = MasterAccount::where('code', "550501")
                                ->where('account_name', 'Uang Muka kepada Vendor')
                                ->where('master_currency_id', $currency_id)->first();
        if(!$dp_id) {
            return redirect()->back()->withErrors(['dp' => 'Please add the account of Uang Muka kepada Vendor with code number is 550501']);
        }
        $dp_id = $dp_id->id;

        $paymentDeatils = PaymentDetail::where('head_id', $id)->get();
        foreach($paymentDeatils as $p) {
            $payable_id = $p->payable_id;
            OrderHead::find($payable_id)->update([
                "status" => "open"
            ]);

            $payment = PaymentHead::whereHas('details', function($query) use ($payable_id) {
                $query->where('payable_id', $payable_id);
            })->get();

            foreach ($payment as $head) {
                $all_not_paid = PaymentDetail::where('head_id', $head->id)
                    ->whereHas('payable', function($query) {
                        $query->where('status', '!=', 'paid');
                    })
                    ->exists();
    
                if ($all_not_paid) {
                    $head->update(['status' => 'open']);
                }
            }
            $p->delete();
        }

        $payment = PaymentHead::find($id);
        $payment->update($data);
        $head_id = $payment->id;

        $totBalance = 0;
        $isDiscount = true;
        $allDetails = [];
        $formData = json_decode($request->input('form_data'), true);
        foreach ($formData as $idx => $data) {
            $payable_id = $data["detail_order"];
            if(!$payable_id) {
                continue;
            }
            if(in_array($payable_id, $allDetails)) {
                continue;
            }
            $remark = $data['detail_remark'];
            $discount_type = $data['detail_discount_type'];
            $discount_nominal = $this->numberToDatabase($data['detail_discount_nominal']);

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
                    if($exchange->from_currency_id === $payment->currency_id) {
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

            if($is_dp == 1) {
                if($idx === 0) {
                    $isDiscount = false;
                }
                $dp_type = $data["detail_dp_type"];
                $dp_nominal = $this->numberToDatabase($data["detail_dp_nominal"]);
            } else {
                OrderHead::find($payable_id)->update([
                    "status" => "paid"
                ]);

                Sao::where('order_id', $payable_id)->update([
                    'isPaid' => true,
                ]);

                $payments = PaymentHead::whereHas('details', function($query) use ($payable_id) {
                    $query->where('payable_id', $payable_id);
                })->get();
        
                foreach ($payments as $head) {
                    $all_paid = PaymentDetail::where('head_id', $head->id)
                        ->whereHas('payable', function($query) {
                            $query->where('status', '!=', 'paid');
                        })
                        ->doesntExist();
        
                    if ($all_paid) {
                        $head->update(['status' => 'paid']);
                    } else {
                        $head->update(['status' => 'open']);
                    }
                }
            }

            $detail = [
                'head_id' => $head_id,
                'payable_id' => $payable_id,
                'discount_type' => $discount_type,
                'discount_nominal' => $discount_nominal,
                'dp_type' => $dp_type,
                'dp_nominal' => $dp_nominal,
                'currency_via_id' => $currency_via_id,
                'amount_via' => $amount_via,
                'remark' => $remark,
            ];  

            $allDetails[] = $payable_id;

            PaymentDetail::create($detail);
        }

        if($isDiscount === true) {
            $payment->update([ "status" => "paid" ]);
        }

        BalanceAccount::where('transaction_id', $id)->where('transaction_type_id', 8)->delete();
        if($display_dp > 0 && $isDiscount === false) {
            $flow = [
                //debit, kredit
                [0, $display_dp, $account_id],
                [$display_dp, 0, $dp_id]
            ];
        } else {
            $flow = [
                //debit, kredit
                [0, $grand_total, $account_id],
                [0, $display_dp_order, $dp_id],
                [0, $discount_display, $diskon_pembelian_id],
                [$additional_cost, 0, $pendapatan_lain_id],
                [$totBalance+$display_dp_order, 0, $hutang_usaha_id]
            ];
        }

        foreach ($flow as $item) {
            $cashflowData = [
                'transaction_id' => $head_id,
                'master_account_id' => $item[2],
                'transaction_type_id' => 8,
                "date" => $date_payment,
                'debit' => $item[0],
                'credit' => $item[1]
            ];
            BalanceAccount::create($cashflowData);
        }

        return redirect()->route('finance.payments.purchase-payment.index')->with('success', 'create successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paymentHead = PaymentHead::findOrFail($id);
        if($this->hasLatestPaymentDetail($paymentHead)) {
            return redirect()->back()->withErrors(['error' => 'Please delete latest payment first']);
        }

        BalanceAccount::where('transaction_id', $id)->where('transaction_type_id', 8)->delete();
        $payment = PaymentDetail::where('head_id', $id)->get();
        foreach($payment as $p) {
            $payable_id = $p->payable_id;
            OrderHead::find($payable_id)->update([
                "status" => "open"
            ]);
            
            $payments = PaymentHead::whereHas('details', function($query) use ($payable_id) {
                $query->where('payable_id', $payable_id);
            })->get();
    
            foreach ($payments as $head) {
                $all_not_paid = PaymentDetail::where('head_id', $head->id)
                    ->whereHas('payable', function($query) {
                        $query->where('status', '!=', 'paid');
                    })
                    ->exists();
    
                if ($all_not_paid) {
                    $head->update(['status' => 'open']);
                }
            }

            $p->delete();
        }

        $paymentHead->delete();

        toast('Data Deleted Successfully!', 'success');
        return redirect()->back()->with('success', 'delete successfully!');
    }

    public function getJurnal($id)
    {
        $data = PaymentHead::find($id);
        return view("financepayments::purchase-payment.jurnal", compact('data'));
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }

    private function hasLatestPaymentDetail($head) {
        $current = PaymentDetail::where('head_id', $head->id)->get();
        foreach ($current as $detail) {
            $latestHead = PaymentHead::whereHas('details', function($query) use ($detail) {
                $query->where('payable_id', $detail->payable_idid);
            })
            ->where('number', '>', $head->number)
            ->pluck('id');

            $latest = PaymentDetail::whereIn('head_id', $latestHead)
                        ->where('payable_id', $detail->payable_id)
                        ->exists();

            if ($latest) {
                return true;
            }
        }

        return false;
    }
}
