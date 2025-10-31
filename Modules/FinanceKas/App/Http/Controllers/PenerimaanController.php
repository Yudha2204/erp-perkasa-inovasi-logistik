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
use Modules\FinanceDataMaster\App\Models\MasterContact;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\MasterTermOfPayment;
use Modules\FinanceKas\App\Models\KasInDetail;
use Modules\FinanceKas\App\Models\KasInHead;
use Modules\FinanceKas\App\Models\NoTransactionsKasIn;
use Modules\Marketing\App\Models\MarketingExport;
use Modules\Marketing\App\Models\MarketingImport;

class PenerimaanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-kas_in@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-kas_in@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-kas_in@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-kas_in@finance', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $head = KasInHead::all();
        return view('financekas::penerimaan.index', compact('head'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $terms = MasterTermOfPayment::all();
        $classifications = ClassificationAccountType::all();
        $no_transactions = NoTransactionsKasIn::all();
        $accountTypes = AccountType::all();
        $currencies = MasterCurrency::all();

        return view('financekas::penerimaan.create', compact('terms', 'classifications', 'no_transactions', 'accountTypes', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
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

        $account_id = $request->input('account_head_id');
        $currency_id = $request->input('currency_id');

        $validator = MasterAccount::where('id', $account_id)->where('master_currency_id', $currency_id)->get()->first();
        if(!$validator) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["error" => 'Please input a valid account']);
        }

        $date_kas_in = $request->input('date');
        $transaction_no = $request->input('no_transactions');
        $exp_transaction = explode("/", $transaction_no);
        $template = "$exp_transaction[0]/$exp_transaction[1]/$exp_transaction[2]/";
        $transaction = NoTransactionsKasIn::where('template', $template)->get()->first();
        if(!$transaction) {
            return redirect()->back()->withErrors(['no_transaction' => 'Please input a valid no transaction'])->withInput();
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
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi'])->withInput();
                }
                $job_order_id = $exp_job_order[0];
                $job_order_source = $exp_job_order[1];
            }
        }

        KasInHead::create([
            'account_id' => $account_id,
            'currency_id' => $currency_id,
            'job_order_id' => $job_order_id,
            'source' => $job_order_source,
            'date_kas_in' => $date_kas_in,
            'transaction_id' => $transaction,
            'number' => $number,
            'description' => $description
        ]);

        $formData = json_decode($request->input('form_data'), true);
        $head_id = KasInHead::latest()->first()->id;

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
            KasInDetail::create([
                'head_id' => $head_id,
                'description' => $description_detail,
                'account_id' => $account_id_detail,
                'total' => $total_detail,
                'remark' => $remark_detail
            ]);
            BalanceAccount::create([
                "master_account_id" => $account_id_detail,
                "transaction_type_id" => 6,
                "currency_id" => $currency_id,
                "transaction_id" => $head_id,
                "date" => $date_kas_in,
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
            if($valid === 0) {
                KasInHead::latest()->first()->delete();
                return redirect()->back()->withErrors($errors)->withInput();
            } else {
                return redirect()->route('finance.kas.pembayaran.edit', $head_id)->withErrors($errors)->withInput();
            }
        }

        BalanceAccount::create([
            "master_account_id" => $account_id,
            "transaction_type_id" => 6,
            "currency_id" => $currency_id,
            "transaction_id" => $head_id,
            "date" => $date_kas_in,
            "debit" => $total,
            "credit" => 0
        ]);

        return redirect()->route('finance.kas.penerimaan.index')->with('success', 'create successfully!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $head = KasInHead::find($id);
        return view('financekas::penerimaan.show', compact('head'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $head = KasInHead::find($id);
        $terms = MasterTermOfPayment::all();
        $classifications = ClassificationAccountType::all();
        $no_transactions = NoTransactionsKasIn::all();
        $accountTypes = AccountType::all();
        $currencies = MasterCurrency::all();

        $currency_id = $head->currency_id;
        $export = MarketingExport::with('quotation')
                ->where('status', 2)
                // ->where('contact_id', $head->contact_id)
                ->get();
        $import = MarketingImport::with('quotation')
                ->where('status', 2)
                // ->where('contact_id', $head->contact_id)
                ->get();
        $marketing = $export->concat($import);

        $accounts = MasterAccount::where('master_currency_id', $head->currency_id)->get();

        return view('financekas::penerimaan.edit', compact('head', 'terms', 'classifications', 'no_transactions', 'accountTypes','currencies', 'marketing', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
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

        $account_id = $request->input('account_head_id');
        $currency_id = $request->input('currency_id');

        $validator = MasterAccount::where('id', $account_id)->where('master_currency_id', $currency_id)->get()->first();
        if(!$validator) {
            toast('Failed to Add Data!','error');
            return redirect()->back()
                        ->withErrors(["error" => 'Please input a valid account']);
        }

        $date_kas_in = $request->input('date');
        $transaction_no = $request->input('no_transactions');
        $exp_transaction = explode("/", $transaction_no);
        $template = "$exp_transaction[0]/$exp_transaction[1]/$exp_transaction[2]/";
        $transaction = NoTransactionsKasIn::where('template', $template)->get()->first();
        if(!$transaction) {
            return redirect()->back()->withErrors(['no_transaction' => 'Please input a valid no transaction'])->withInput();
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
                    return redirect()->back()->withErrors(['no_referensi' => 'Please input a valid no referensi'])->withInput();
                }
                $job_order_id = $exp_job_order[0];
                $job_order_source = $exp_job_order[1];
            }
        }

        $kas = KasInHead::find($id);

        $formData = json_decode($request->input('form_data'), true);

        $total = 0;
        $currentBalanceAccount = BalanceAccount::where("transaction_type_id", 6)->where('transaction_id', $id)->get();

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
                KasInDetail::create([
                    'head_id' => $id,
                    'description' => $description_detail,
                    'account_id' => $account_id_detail,
                    'total' => $total_detail,
                    'remark' => $remark_detail
                ]);
            } else if($exp_operator[1] === "update") {
                $kasDetail = KasInDetail::find($exp_operator[0]);
                $kasDetail->update([
                    'description' => $description_detail,
                    'account_id' => $account_id_detail,
                    'total' => $total_detail,
                    'remark' => $remark_detail
                ]);
            } else if($exp_operator[1] === "delete") {
                $kasDetail = KasInDetail::find($exp_operator[0]);
                if($kasDetail) {
                    $kasDetail->delete();
                }
            }

            BalanceAccount::create([
                "master_account_id" => $account_id_detail,
                "transaction_type_id" => 6,
                "currency_id" => $currency_id,
                "transaction_id" => $id,
                "date" => $date_kas_in,
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
            'account_id' => $account_id,
            'currency_id' => $currency_id,
            'job_order_id' => $job_order_id,
            'source' => $job_order_source,
            'date_kas_in' => $date_kas_in,
            'transaction_id' => $transaction,
            'number' => $number,
            'description' => $description
        ]);

        BalanceAccount::create([
            "master_account_id" => $account_id,
            "transaction_type_id" => 6,
            "currency_id" => $currency_id,
            "transaction_id" => $id,
            "date" => $date_kas_in,
            "debit" => $total,
            "credit" => 0
        ]);

        return redirect()->route('finance.kas.penerimaan.index')->with('success', 'create successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        KasInDetail::where('head_id', $id)->delete();
        BalanceAccount::where('transaction_id', $id)->where('transaction_type_id', 6)->delete();

        $penerimaan = KasInHead::findOrFail($id);
        $penerimaan->delete();

        toast('Data Deleted Successfully!', 'success');
        return redirect()->back();
    }

    public function getJurnal($id)
    {
        $jurnal = KasInHead::find($id);
        return view('financekas::penerimaan.jurnal', [
            'jurnal' => $jurnal
            // 'title' => 'Journal Cash & Bank In',
            // 'transactionNumber' => $kas->transaction,
            // 'transactionDate' => $kas->date_kas_in,
            // 'description' => $kas->description,
            // 'jurnals' => $kas->jurnal,
            // 'currency' => $kas->currency->initial,
            // 'backUrl' => route('finance.kas.penerimaan.index'),
            // 'jurnalsIDR' => null,
        ]);
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }
}
