<?php

namespace Modules\FinanceKas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\ClassificationAccountType;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceKas\App\Models\KasOutHead;
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinanceKas\App\Models\KasOutDetail;
use Modules\FinanceKas\App\Models\NoTransactionsKasOut;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-kas_out@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-kas_out@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-kas_out@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-kas_out@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $head = KasOutHead::all();
        return view('financekas::pembayaran.index', compact('head'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $classifications = ClassificationAccountType::all();
        $no_transactions = NoTransactionsKasOut::all();
        $accountTypes = AccountType::all();
        $currencies = MasterCurrency::all();

        return view('financekas::pembayaran.create', compact('contact', 'terms', 'classifications', 'no_transactions', 'accountTypes', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            // 'customer_id'   => 'required',
            'account_head_id' => 'required',
            'currency_id'    => 'required',
            'date' => 'required',
            'no_transactions' => 'required'
        ]);

        if ($validator->fails()) {
            toast('failed to add data!','error');
            return redirect()->back()
                        ->withErrors($validator);
        }

        // $contact_id = $request->input('customer_id');
        $contact_id = null;
        $account_id = $request->input('account_head_id');
        $currency_id = $request->input('currency_id');

        $validator = MasterAccount::where('id', $account_id)->where('master_currency_id', $currency_id)->get()->first();
        if(!$validator) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["error" => 'Please input a valid account']);
        }

        $date_kas_out = $request->input('date');
        $transaction_no = $request->input('no_transactions');
        $exp_transaction = explode("/", $transaction_no);
        $template = "$exp_transaction[0]/$exp_transaction[1]/$exp_transaction[2]/";
        $transaction = NoTransactionsKasOut::where('template', $template)->get()->first();
        if(!$transaction) {
            return redirect()->back()->withErrors(['no_transaction' => 'Please input a valid no transaction']);
        }
        $transaction = $transaction->id;
        $number = $exp_transaction[3];
        $description = $request->input('description');

        $is_job_order = $request->input('choose_job_order');
        $job_order_id = null;
        $job_order_source = null;
        if($is_job_order === "1") {
            $job_order = $request->input('job_order');
            if($job_order) {
                $exp_job_order = explode(":", $job_order);
                if(sizeof($exp_job_order) !== 2) {
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi']);
                }
                $job_order_id = $exp_job_order[0];
                $job_order_source = $exp_job_order[1];
            }
        }

        KasOutHead::create([
            'contact_id' => $contact_id,
            'account_id' => $account_id,
            'currency_id' => $currency_id,
            'job_order_id' => $job_order_id,
            'source' => $job_order_source,
            'date_kas_out' => $date_kas_out,
            'transaction_id' => $transaction,
            'number' => $number,
            'description' => $description
        ]);

        $formData = json_decode($request->input('form_data'), true);
        $head_id = KasOutHead::latest()->first()->id;

        $total = 0;
        $errorSame = 0;
        $errorInvalid = 0;
        $valid = 0;
        foreach ($formData as $data) {
            $description_detail = $data['desc_detail'];
            $account_id_detail = $data['account_detail_id'];
            $total_detail = $data['price_detail'];
            $remark_detail = $data['remark_detail'];
            if($account_id_detail === $account_id) {
                $errorSame++;
                continue;
            }

            $validator = MasterAccount::where('id', $account_id_detail)->where('master_currency_id', $currency_id)->get()->first();
            if(!$validator) {
                $errorInvalid++;
                continue;
            }

            $total_detail = $this->numberToDatabase($total_detail);

            $total += $total_detail;
            $valid++;
            KasOutDetail::create([
                'head_id' => $head_id,
                'description' => $description_detail,
                'account_id' => $account_id_detail,
                'total' => $total_detail,
                'remark' => $remark_detail
            ]);
            BalanceAccount::create([
                "master_account_id" => $account_id_detail,
                "transaction_type_id" => 5,
                "transaction_id" => $head_id,
                "date" => $date_kas_out,
                "debit" => $total_detail,
                "credit" => 0
            ]);
        }

        if($errorInvalid > 0 || $errorSame > 0) {
            $errors = [];
            if($errorSame > 0) {
                $errors['same'] = "There are $errorSame same account as your from account";
            }
            if($errorInvalid > 0) {
                $errors["invalid"] = "There are $errorInvalid invalid account";
            }
            if($valid === 0) {
                KasOutHead::latest()->first()->delete();
                return redirect()->back()->withErrors($errors)->withInput();
            } else {
                return redirect()->route('finance.kas.pembayaran.edit', $head_id)->withErrors($errors)->withInput();
            }
        }

        BalanceAccount::create([
            "master_account_id" => $account_id,
            "transaction_type_id" => 5,
            "transaction_id" => $head_id,
            "date" => $date_kas_out,
            "debit" => 0,
            "credit" => $total
        ]);

        return redirect()->route('finance.kas.pembayaran.index')->with('success', 'create successfully!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $head = KasOutHead::find($id);
        return view('financekas::pembayaran.show', compact('head'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $head = KasOutHead::find($id);
        $contact = MasterContact::whereJsonContains('type','1')->get();
        $terms = MasterTermOfPayment::all();
        $classifications = ClassificationAccountType::all();
        $no_transactions = NoTransactionsKasOut::all();
        $accountTypes = AccountType::all();
        $currencies = MasterCurrency::all();

        $currency_id = $head->currency_id;
        $export = MarketingExport::with('quotation')
                ->where('status', 2)
                ->where('contact_id', $head->contact_id)
                ->get();
        $import = MarketingImport::with('quotation')
                ->where('status', 2)
                ->where('contact_id', $head->contact_id)
                ->get();
        $marketing = $export->concat($import);

        $accounts = MasterAccount::where('master_currency_id', $head->currency_id)->get();

        return view('financekas::pembayaran.edit', compact('head', 'contact', 'terms', 'classifications', 'no_transactions', 'accountTypes','currencies','marketing', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            // 'customer_id'   => 'required',
            'account_head_id' => 'required',
            'currency_id'    => 'required',
            'date' => 'required',
            'no_transactions' => 'required'
        ]);

        if ($validator->fails()) {
            toast('failed to add data!','error');
            return redirect()->back()
                        ->withErrors($validator);
        }

        // $contact_id = $request->input('customer_id');
        $contact_id = null;
        $account_id = $request->input('account_head_id');
        $currency_id = $request->input('currency_id');

        $validator = MasterAccount::where('id', $account_id)->where('master_currency_id', $currency_id)->get()->first();
        if(!$validator) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["error" => 'Please input a valid account']);
        }

        $date_kas_out = $request->input('date');
        $transaction_no = $request->input('no_transactions');
        $exp_transaction = explode("/", $transaction_no);
        $template = "$exp_transaction[0]/$exp_transaction[1]/$exp_transaction[2]/";
        $transaction = NoTransactionsKasOut::where('template', $template)->get()->first();
        if(!$transaction) {
            return redirect()->back()->withErrors(['no_transaction' => 'Please input a valid no transaction']);
        }
        $transaction = $transaction->id;
        $number = $exp_transaction[3];
        $description = $request->input('description');

        $is_job_order = $request->input('choose_job_order');
        $job_order_id = null;
        $job_order_source = null;
        if($is_job_order === "1") {
            $job_order = $request->input('job_order');
            if($job_order) {
                $exp_job_order = explode(":", $job_order);
                if(sizeof($exp_job_order) !== 2) {
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi']);
                }
                $job_order_id = $exp_job_order[0];
                $job_order_source = $exp_job_order[1];
            }
        }

        $kas = KasOutHead::find($id);

        $formData = json_decode($request->input('form_data'), true);

        $total = 0;
        $currentBalanceAccount = BalanceAccount::where("transaction_type_id", 5)->where('transaction_id', $id)->get();

        $errorSame = 0;
        $errorInvalid = 0;
        foreach ($formData as $index => $data) {
            $description_detail = $data['desc_detail'];
            $account_id_detail = $data['account_detail_id'];
            $total_detail = $data['price_detail'];
            $remark_detail = $data['remark_detail'];
            if($account_id_detail === $account_id) {
                $errorSame++;
                continue;
            }

            $validator = MasterAccount::where('id', $account_id_detail)->where('master_currency_id', $currency_id)->get()->first();
            if(!$validator) {
                $errorInvalid++;
                continue;
            }

            $total_detail = $this->numberToDatabase($total_detail);

            $operator = $data['operator'];
            $exp_operator = explode(":", $operator);

            $total += $total_detail;
            if($exp_operator[1] === "create") {
                KasOutDetail::create([
                    'head_id' => $id,
                    'description' => $description_detail,
                    'account_id' => $account_id_detail,
                    'total' => $total_detail,
                    'remark' => $remark_detail
                ]);
            } else if($exp_operator[1] === "update") {
                $kasDetail = KasOutDetail::find($exp_operator[0]);
                $kasDetail->update([
                    'description' => $description_detail,
                    'account_id' => $account_id_detail,
                    'total' => $total_detail,
                    'remark' => $remark_detail
                ]);
            } else if($exp_operator[1] === "delete") {
                $kasDetail = KasOutDetail::find($exp_operator[0]);
                if($kasDetail) {
                    $kasDetail->delete();
                }
            }

            BalanceAccount::create([
                "master_account_id" => $account_id_detail,
                "transaction_type_id" => 5,
                "transaction_id" => $id,
                "date" => $date_kas_out,
                "debit" => 0,
                "credit" => $total_detail
            ]);
        }

        if($errorInvalid > 0 || $errorSame > 0) {
            $errors = [];
            if($errorSame > 0) {
                $errors['same'] = "There are $errorSame same account as your from account";
            }
            if($errorInvalid > 0) {
                $errors["invalid"] = "There are $errorInvalid invalid account";
            }
            return redirect()->back()->withErrors($errors)->withInput();
        }

        foreach($currentBalanceAccount as $c_balance) {
            $c_balance->delete();
        }
        $kas->update([
            'contact_id' => $contact_id,
            'account_id' => $account_id,
            'currency_id' => $currency_id,
            'job_order_id' => $job_order_id,
            'source' => $job_order_source,
            'date_kas_out' => $date_kas_out,
            'transaction_id' => $transaction,
            'number' => $number,
            'description' => $description
        ]);

        BalanceAccount::create([
            "master_account_id" => $account_id,
            "transaction_type_id" => 5,
            "transaction_id" => $id,
            "date" => $date_kas_out,
            "debit" => $total,
            "credit" => 0
        ]);

        return redirect()->route('finance.kas.pembayaran.index')->with('success', 'create successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        KasOutDetail::where('head_id', $id)->delete();
        BalanceAccount::where('transaction_id', $id)->where('transaction_type_id', 5)->delete();

        $pembayaran = KasOutHead::findOrFail($id);
        $pembayaran->delete();

        toast('Data Deleted Successfully!', 'success');
        return redirect()->back();
    }

    public function getJurnal($id)
    {
        $jurnal = KasOutHead::find($id);
        return view('financekas::pembayaran.jurnal', [
            'jurnal' => $jurnal
            // 'title' => 'Journal Cash & Bank Out',
            // 'transactionNumber' => $kas->transaction,
            // 'transactionDate' => $kas->date_kas_out,
            // 'description' => $kas->description,
            // 'jurnals' => $kas->jurnal,
            // 'currency' => $kas->currency->initial,
            // 'backUrl' => route('finance.kas.pembayaran.index'),
            // 'jurnalsIDR' => null,
        ]);
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
