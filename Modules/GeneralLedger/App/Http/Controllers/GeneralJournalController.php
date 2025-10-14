<?php

namespace Modules\GeneralLedger\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\GeneralLedger\App\Models\GeneralJournalHead;
use Modules\GeneralLedger\App\Models\GeneralJournalDetail;

class GeneralJournalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-general_journal@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-general_journal@finance', ['only' => ['create','store']]);
        $this->middleware('permission:edit-general_journal@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-general_journal@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GeneralJournalHead::with(['currency', 'details.account']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('journal_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('currency', function($currencyQuery) use ($search) {
                      $currencyQuery->where('initial', 'like', "%{$search}%")
                                   ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $journals = $query->orderBy('date', 'desc')->get();
        
        return view('generalledger::general-journal.index', compact('journals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currencies = MasterCurrency::all();
        $accounts = MasterAccount::with('currency')->get();
        
        // Get latest journal number for current year
        $current_year = \Carbon\Carbon::now()->year;
        $latest_journal = GeneralJournalHead::whereYear('date', $current_year)
                    ->withTrashed()
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($latest_journal) {
            // Extract number from journal_number (format: GJ.2024-10-0001)
            $journal_parts = explode('-', $latest_journal->journal_number);
            if(count($journal_parts) >= 3) {
                $latest_number = intval($journal_parts[2]) + 1;
            }
        }

        return view('generalledger::general-journal.create', compact('currencies', 'accounts', 'latest_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'journal_number' => 'required|string|unique:general_journal_heads',
            'date' => 'required|date',
            'currency_id' => 'required|exists:master_currency,id',
            'description' => 'nullable|string',
            'form_data' => 'required|string'
        ]);

        if ($validator->fails()) {
            toast('Failed to add data!', 'error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formData = json_decode($request->input('form_data'), true);
        
        if (empty($formData)) {
            toast('Please add at least one journal entry!', 'error');
            return redirect()->back()->withInput();
        }

        // Validate form data
        $totalDebit = 0;
        $totalCredit = 0;
        $errors = [];

        foreach ($formData as $index => $data) {
            $debit = $this->numberToDatabase($data['debit'] ?? 0);
            $credit = $this->numberToDatabase($data['credit'] ?? 0);
            $accountId = $data['account_id'] ?? null;

            if (!$accountId) {
                $errors[] = "Row " . ($index + 1) . ": Account is required";
                continue;
            }

            // Validate account exists and matches currency
            // $account = MasterAccount::where('id', $accountId)
            //     ->where('master_currency_id', $request->input('currency_id'))
            //     ->first();

            // if (!$account) {
            //     $errors[] = "Row " . ($index + 1) . ": Invalid account for selected currency";
            //     continue;
            // }

            if ($debit > 0 && $credit > 0) {
                $errors[] = "Row " . ($index + 1) . ": Cannot have both debit and credit amounts";
                continue;
            }

            if ($debit == 0 && $credit == 0) {
                $errors[] = "Row " . ($index + 1) . ": Must have either debit or credit amount";
                continue;
            }

            $totalDebit += $debit;
            $totalCredit += $credit;
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors(['form_errors' => $errors])->withInput();
        }

        // Check if debits equal credits
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return redirect()->back()->withErrors(['balance' => 'Total debits must equal total credits'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Create journal head
            $journalHead = GeneralJournalHead::create([
                'journal_number' => $request->input('journal_number'),
                'date' => $request->input('date'),
                'currency_id' => $request->input('currency_id'),
                'description' => $request->input('description'),
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit
            ]);

            // Create journal details and balance accounts
            foreach ($formData as $data) {
                $debit = $this->numberToDatabase($data['debit'] ?? 0);
                $credit = $this->numberToDatabase($data['credit'] ?? 0);
                $accountId = $data['account_id'];

                if ($debit > 0 || $credit > 0) {
                    // Create journal detail
                    GeneralJournalDetail::create([
                        'head_id' => $journalHead->id,
                        'account_id' => $accountId,
                        'description' => $data['description'] ?? null,
                        'debit' => $debit,
                        'credit' => $credit,
                        'remark' => $data['remark'] ?? null
                    ]);

                    // Create balance account entry
                    BalanceAccount::create([
                        'master_account_id' => $accountId,
                        'transaction_type_id' => 9, // General Journal
                        'transaction_id' => $journalHead->id,
                        'date' => $request->input('date'),
                        'debit' => $debit,
                        'credit' => $credit,
                        'currency_id' => $request->input('currency_id')
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('generalledger.general-journal.index')->with('success', 'General Journal created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create journal: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $journal = GeneralJournalHead::with(['currency', 'details.account'])->findOrFail($id);
        return view('generalledger::general-journal.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $journal = GeneralJournalHead::with(['currency', 'details.account'])->findOrFail($id);
        $currencies = MasterCurrency::all();
        $accounts = MasterAccount::with('currency')->get();
        return view('generalledger::general-journal.edit', compact('journal', 'currencies', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $journal = GeneralJournalHead::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'journal_number' => 'required|string|unique:general_journal_heads,journal_number,' . $id,
            'date' => 'required|date',
            'currency_id' => 'required|exists:master_currency,id',
            'description' => 'nullable|string',
            'form_data' => 'required|string'
        ]);

        if ($validator->fails()) {
            toast('Failed to update data!', 'error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formData = json_decode($request->input('form_data'), true);
        
        if (empty($formData)) {
            toast('Please add at least one journal entry!', 'error');
            return redirect()->back()->withInput();
        }

        // Validate form data
        $totalDebit = 0;
        $totalCredit = 0;
        $errors = [];

        foreach ($formData as $index => $data) {
            $debit = $this->numberToDatabase($data['debit'] ?? 0);
            $credit = $this->numberToDatabase($data['credit'] ?? 0);
            $accountId = $data['account_id'] ?? null;

            if (!$accountId) {
                $errors[] = "Row " . ($index + 1) . ": Account is required";
                continue;
            }

            // Validate account exists and matches currency
            // $account = MasterAccount::where('id', $accountId)
            //     ->where('master_currency_id', $request->input('currency_id'))
            //     ->first();

            // if (!$account) {
            //     $errors[] = "Row " . ($index + 1) . ": Invalid account for selected currency";
            //     continue;
            // }

            if ($debit > 0 && $credit > 0) {
                $errors[] = "Row " . ($index + 1) . ": Cannot have both debit and credit amounts";
                continue;
            }

            if ($debit == 0 && $credit == 0) {
                $errors[] = "Row " . ($index + 1) . ": Must have either debit or credit amount";
                continue;
            }

            $totalDebit += $debit;
            $totalCredit += $credit;
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors(['form_errors' => $errors])->withInput();
        }

        // Check if debits equal credits
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return redirect()->back()->withErrors(['balance' => 'Total debits must equal total credits'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Delete existing balance accounts
            BalanceAccount::where('transaction_type_id', 9)
                ->where('transaction_id', $id)
                ->delete();

            // Delete existing details
            GeneralJournalDetail::where('head_id', $id)->delete();

            // Update journal head
            $journal->update([
                'journal_number' => $request->input('journal_number'),
                'date' => $request->input('date'),
                'currency_id' => $request->input('currency_id'),
                'description' => $request->input('description'),
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit
            ]);

            // Create new journal details and balance accounts
            foreach ($formData as $data) {
                $debit = $this->numberToDatabase($data['debit'] ?? 0);
                $credit = $this->numberToDatabase($data['credit'] ?? 0);
                $accountId = $data['account_id'];

                if ($debit > 0 || $credit > 0) {
                    // Create journal detail
                    GeneralJournalDetail::create([
                        'head_id' => $journal->id,
                        'account_id' => $accountId,
                        'description' => $data['description'] ?? null,
                        'debit' => $debit,
                        'credit' => $credit,
                        'remark' => $data['remark'] ?? null
                    ]);

                    // Create balance account entry
                    BalanceAccount::create([
                        'master_account_id' => $accountId,
                        'transaction_type_id' => 9, // General Journal
                        'transaction_id' => $journal->id,
                        'date' => $request->input('date'),
                        'debit' => $debit,
                        'credit' => $credit,
                        'currency_id' => $request->input('currency_id')
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('generalledger.general-journal.index')->with('success', 'General Journal updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update journal: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Delete balance accounts
            BalanceAccount::where('transaction_type_id', 9)
                ->where('transaction_id', $id)
                ->delete();

            // Delete details
            GeneralJournalDetail::where('head_id', $id)->delete();

            // Delete head
            $journal = GeneralJournalHead::findOrFail($id);
            $journal->delete();

            DB::commit();
            toast('General Journal deleted successfully!', 'success');
            return redirect()->back();

        } catch (Exception $e) {
            DB::rollBack();
            toast('Failed to delete journal: ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Get transaction number based on date
     */
    public function getTransactionNumber(Request $request)
    {
        $date = $request->get('date');
        $year = \Carbon\Carbon::parse($date)->year;
        $month = \Carbon\Carbon::parse($date)->month;
        
        $latest_journal = GeneralJournalHead::whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->latest()
                    ->first();

        $latest_number = 1;
        if($latest_journal) {
            // Extract number from journal_number (format: GJ.2024-10-0001)
            $journal_parts = explode('-', $latest_journal->journal_number);
            if(count($journal_parts) >= 3) {
                $latest_number = intval($journal_parts[2]) + 1;
            }
        }

        return response()->json([
            'message' => "Success",
            'data' => $latest_number
        ]);
    }

    private function numberToDatabase($string)
    {
        $replace = str_replace(',', '', $string);
        return floatval($replace);
    }

    public function getJurnal($id)
    {
        $data = GeneralJournalHead::find($id);
        return view('generalledger::general-journal.jurnal', compact('data'));
    }
}
