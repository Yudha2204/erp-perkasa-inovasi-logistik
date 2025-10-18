<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setup;
use Illuminate\Http\Request;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Illuminate\Support\Facades\Validator;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\ClassificationAccountType;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePayments\App\Models\OrderHead;

class AccountDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-account@finance', ['only' => ['index','show']]);
        $this->middleware('permission:create-account@finance', ['only' => ['create','store','storeBeginningBalance']]);
        $this->middleware('permission:edit-account@finance', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-account@finance', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function searchFilterIndex($search){
        $index = MasterAccount::query()->with('balance_accounts');

        if($search) {
            $index
            ->where('code','like',"%".$search."%")
            ->orWhere('account_name','like',"%".$search."%");
        }

        return $index->paginate(10);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $accounts = $this->searchFilterIndex($search);
        } else {
            $accounts = MasterAccount::orderBy('id', 'ASC')->with('balance_accounts')->paginate(10);
        }

        $accountTypes = AccountType::all();
        $currencies = MasterCurrency::all();
        $headerAccounts = MasterAccount::where('type', 'header')->get();

        return view('financedatamaster::account.index', compact('accounts', 'accountTypes', 'currencies', 'headerAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financedatamaster::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->id) {
            //validate store
            $validator = Validator::make($request->all(), [
                'code'     => 'required|unique:master_account',
                'account_name'   => 'required'
            ]);

            if ($validator->fails()) {
                $account = MasterAccount::where('code', $request->code)
                            ->where('master_currency_id', $request->master_currency_id)
                            ->get()->isEmpty();
                if(!$account) {
                    toast('failed to add data!','error');
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
            }
        } else {
            //validate edit
            $validator = Validator::make($request->all(),[
                'code'     => 'required|unique:master_account,code,'.$request->id,
                'account_name'   => 'required'
               ],
               [
                 'code.unique'=> 'The code '.$request->code.' has already been taken', // custom message
                ]
            );

            if ($validator->fails()) {
                $account = MasterAccount::where('code', $request->code)
                            ->where('master_currency_id', $request->master_currency_id)
                            ->whereNot('id', $request->id)
                            ->get()->isEmpty();
                if(!$account) {
                    toast('failed to update data!','error');
                    return redirect()->back()
                                ->withErrors($validator)->withInput();
                }
            }
        }

        MasterAccount::updateOrCreate([
            'id' => $request->id
        ],
        [
            'account_type_id' => $request->account_type_id ?? null,
            'code' => $request->code,
            'account_name' => $request->account_name,
            'master_currency_id' => $request->master_currency_id ?? null,
            'type' => $request->type,
            'parent' => $request->parent ?? null,
            'can_delete' => 1
        ]);

        toast('Data Saved Successfully!','success');
        return redirect()->back();
    }

    public function storeBeginningBalance(Request $request)
    {
        $date = Setup::getStartEntryPeriod();

        if (!$date) {
            toast('Start Entry Period is not set!','error');
            return redirect()->back();
        }

        // Get the master account to check its type
        $masterAccount = MasterAccount::with('account_type')->find($request->id_account);
        
        if (!$masterAccount) {
            toast('Account not found!','error');
            return redirect()->back();
        }

        // Determine debit/credit based on account type and normal side
        $debit = 0;
        $credit = 0;
        
        // Check if this is an AR (Account Receivable) or AP (Account Payable) account by account_type_id
        $accountTypeId = $masterAccount->account_type_id;
        
        // For AR/AP accounts, individual balance accounts will be created in their respective methods
        if ($accountTypeId == 4 || $accountTypeId == 8) {
            // Skip the single balance account creation for AR/AP accounts
            // Individual balance accounts will be created for each invoice/AP entry
        } else {
            // For other accounts, use the provided debit/credit values and create single balance account
            $debit = $request->debit ?? 0;
            $credit = $request->credit ?? 0;
            
            // Create the beginning balance entry in balance_account_data with transaction_type_id = 1
            $balanceAccount = BalanceAccount::updateOrCreate([
                'id' => $request->id_balance_account
            ],
            [
                'master_account_id' => $request->id_account,
                'date' => $date, // Use setup start entry period date
                'transaction_type_id' => 1,
                'debit' => $debit,
                'credit' => $credit,
                'currency_id' => $masterAccount->master_currency_id ?? 1,
            ]);
        }

        // Handle Account Receivable (AR) accounts - ID 4
        if ($accountTypeId == 4) {
            // Validate invoice entries
            $invoiceEntries = json_decode($request->invoice_entries, true) ?? [];

            $this->createMultipleBeginningBalanceInvoices($invoiceEntries, $masterAccount);
            toast('Beginning balance saved with Invoice entries!','success');
            return redirect()->back();
        }
        
        // Handle Account Payable (AP) accounts - ID 8
        if ($accountTypeId == 8) {
            // Validate AP entries
            $apEntries = json_decode($request->ap_entries, true) ?? [];

            $this->createMultipleBeginningBalanceAPs($apEntries, $masterAccount);
            toast('Beginning balance saved with Account Payable entries!','success');
            return redirect()->back();
        }

        // For all other account types, only the balance account entry is created
        toast('Beginning balance saved successfully!','success');
        return redirect()->back();
    }

    private function createMultipleBeginningBalanceInvoices($invoiceEntries, $masterAccount)
    {
        // Get existing beginning balance invoices for this account
        $existingInvoices = InvoiceHead::where('account_id', $masterAccount->id)
            ->where('status', 'Beginning Balance')
            ->get();

        // Create arrays to track which entries we're keeping
        $existingIds = $existingInvoices->pluck('id')->toArray();
        $newIds = [];

        // Process each entry from the form
        foreach ($invoiceEntries as $entry) {
            if (isset($entry['id']) && is_numeric($entry['id']) && in_array($entry['id'], $existingIds)) {
                // Update existing invoice
                $invoiceHead = InvoiceHead::find($entry['id']);
                if ($invoiceHead) {
                    $invoiceHead->update([
                        'number' => (int) $entry['number'],
                        'date_invoice' => $entry['date'],
                        'description' => 'Beginning Balance - ' . $masterAccount->account_name,
                    ]);

                    // Update invoice detail
                    $invoiceDetail = \Modules\FinancePiutang\App\Models\InvoiceDetail::where('head_id', $invoiceHead->id)->first();
                    if ($invoiceDetail) {
                        $invoiceDetail->update([
                            'price' => $entry['value'],
                        ]);
                    }

                    // Update balance account entry
                    $normalSide = $masterAccount->account_type->normal_side ?? 'debit';
                    $debit = $normalSide === 'debit' ? $entry['value'] : 0;
                    $credit = $normalSide === 'credit' ? $entry['value'] : 0;

                    $balanceAccount = BalanceAccount::where('master_account_id', $masterAccount->id)
                        ->where('transaction_type_id', 1)
                        ->where('transaction_id', $invoiceHead->id)
                        ->first();

                    if ($balanceAccount) {
                        $balanceAccount->update([
                            'debit' => $debit,
                            'credit' => $credit,
                        ]);
                    }

                    $newIds[] = $entry['id'];
                }
            } else {
                // Create new invoice
                $invoiceHead = InvoiceHead::create([
                    'contact_id' => null,
                    'sales_id' => null,
                    'term_payment' => null,
                    'currency_id' => $masterAccount->master_currency_id ?? 1,
                    'number' => (int) $entry['number'],
                    'date_invoice' => $entry['date'],
                    'description' => 'Beginning Balance - ' . $masterAccount->account_name,
                    'additional_cost' => 0,
                    'discount_type' => null,
                    'discount_nominal' => 0,
                    'status' => 'Beginning Balance',
                    'account_id' => $masterAccount->id,
                ]);

                // Create invoice detail
                $invoiceDetail = \Modules\FinancePiutang\App\Models\InvoiceDetail::create([
                    'head_id' => $invoiceHead->id,
                    'description' => 'Beginning Balance Entry',
                    'quantity' => 1,
                    'uom' => 'Unit',
                    'price' => $entry['value'],
                    'tax_id' => null,
                    'remark' => 'Beginning Balance',
                    'discount_type' => null,
                    'discount_nominal' => 0,
                    'dp_type' => null,
                    'dp_nominal' => 0,
                ]);

                // Create balance account entry
                $normalSide = $masterAccount->account_type->normal_side ?? 'debit';
                $debit = $normalSide === 'debit' ? $entry['value'] : 0;
                $credit = $normalSide === 'credit' ? $entry['value'] : 0;

                BalanceAccount::create([
                    'master_account_id' => $masterAccount->id,
                    'date' => \App\Models\Setup::getStartEntryPeriod(),
                    'transaction_type_id' => 1,
                    'transaction_id' => $invoiceHead->id,
                    'debit' => $debit,
                    'credit' => $credit,
                    'currency_id' => $masterAccount->master_currency_id ?? 1,
                ]);

                $newIds[] = $invoiceHead->id;
            }
        }

        // No deletion - just update existing records
    }
    
    public function updateBeginningBalance(Request $request)
    {
        
        if ($request->type == "invoice") {
            $this->updateBeginningBalanceInvoice($request);
        } else if ($request->type == "ap") {
            $this->updateBeginningBalanceAP($request);
        }
        
        toast('Beginning balance updated successfully!','success');
        return redirect()->back();
    }
    
    private function updateBeginningBalanceInvoice($request)
    {
        $invoiceHead = InvoiceHead::find($request->id);
        if ($invoiceHead) {
            $invoiceHead->update([
                'number' => $request->number_edit_beginning_balance,
                'date_invoice' => $request->date_edit_beginning_balance,
            ]);

            $invoiceDetail = \Modules\FinancePiutang\App\Models\InvoiceDetail::where('head_id', $invoiceHead->id)->first();
            if ($invoiceDetail) {
                $invoiceDetail->update([
                    'price' => $request->value_edit_beginning_balance,
                ]);
            }

            $balanceAccount = BalanceAccount::where('master_account_id', $invoiceHead->account_id)
                ->where('transaction_type_id', 1)
                ->where('transaction_id', $invoiceHead->id)
                ->first();

            if ($balanceAccount) {
                $balanceAccount->update([
                    'debit' => $request->value_edit_beginning_balance,
                ]);
            }
        }
    }

    private function updateBeginningBalanceAP($request)
    {
        $orderHead = OrderHead::find($request->id);
        if ($orderHead) {
            $orderHead->update([
                'transaction' => $request->number_edit_beginning_balance,
                'date_order' => $request->date_edit_beginning_balance,
            ]);

            $orderDetail = \Modules\FinancePayments\App\Models\OrderDetail::where('head_id', $orderHead->id)->first();
            if ($orderDetail) {
                $orderDetail->update([
                    'price' => $request->value_edit_beginning_balance,
                ]);
            }
        }

        $balanceAccount = BalanceAccount::where('master_account_id', $orderHead->account_id)
            ->where('transaction_type_id', 1)
            ->where('transaction_id', $orderHead->id)
            ->first();

        if ($balanceAccount) {
            $balanceAccount->update([
                'debit' => $request->value_edit_beginning_balance,
            ]);
        }
    }

    private function createMultipleBeginningBalanceAPs($apEntries, $masterAccount)
    {
        // Get existing beginning balance APs for this account
        $existingAPs = OrderHead::where('account_id', $masterAccount->id)
            ->where('status', 'Beginning Balance')
            ->get();

        // Create arrays to track which entries we're keeping
        $existingIds = $existingAPs->pluck('id')->toArray();
        $newIds = [];

        // Process each entry from the form
        foreach ($apEntries as $entry) {
            if (isset($entry['id']) && is_numeric($entry['id']) && in_array($entry['id'], $existingIds)) {
                // Update existing AP
                $orderHead = OrderHead::find($entry['id']);
                if ($orderHead) {
                    $orderHead->update([
                        'transaction' => $entry['number'],
                        'date_order' => $entry['date'],
                        'description' => 'Beginning Balance - ' . $masterAccount->account_name,
                    ]);

                    // Update order detail
                    $orderDetail = \Modules\FinancePayments\App\Models\OrderDetail::where('head_id', $orderHead->id)->first();
                    if ($orderDetail) {
                        $orderDetail->update([
                            'price' => $entry['value'],
                        ]);
                    }

                    // Update balance account entry
                    $normalSide = $masterAccount->account_type->normal_side ?? 'credit';
                    $debit = $normalSide === 'debit' ? $entry['value'] : 0;
                    $credit = $normalSide === 'credit' ? $entry['value'] : 0;

                    $balanceAccount = BalanceAccount::where('master_account_id', $masterAccount->id)
                        ->where('transaction_type_id', 1)
                        ->where('transaction_id', $orderHead->id)
                        ->first();

                    if ($balanceAccount) {
                        $balanceAccount->update([
                            'debit' => $debit,
                            'credit' => $credit,
                        ]);
                    }

                    $newIds[] = $entry['id'];
                }
            } else {
                // Create new AP
                $orderHead = OrderHead::create([
                    'vendor_id' => null,
                    'customer_id' => null,
                    'currency_id' => $masterAccount->master_currency_id ?? 1,
                    'operation_id' => null,
                    'source' => 'Beginning Balance',
                    'transit_via' => null,
                    'transaction' => $entry['number'],
                    'date_order' => $entry['date'],
                    'description' => 'Beginning Balance - ' . $masterAccount->account_name,
                    'additional_cost' => 0,
                    'discount_type' => null,
                    'discount_nominal' => 0,
                    'status' => 'Beginning Balance',
                    'account_id' => $masterAccount->id,
                ]);

                // Create order detail
                $orderDetail = \Modules\FinancePayments\App\Models\OrderDetail::create([
                    'head_id' => $orderHead->id,
                    'description' => 'Beginning Balance Entry',
                    'quantity' => 1,
                    'uom' => 'Unit',
                    'price' => $entry['value'],
                    'tax_id' => null,
                    'remark' => 'Beginning Balance',
                    'discount_type' => null,
                    'discount_nominal' => 0,
                    'dp_type' => null,
                    'dp_nominal' => 0,
                ]);

                // Create balance account entry
                $normalSide = $masterAccount->account_type->normal_side ?? 'credit';
                $debit = $normalSide === 'debit' ? $entry['value'] : 0;
                $credit = $normalSide === 'credit' ? $entry['value'] : 0;

                BalanceAccount::create([
                    'master_account_id' => $masterAccount->id,
                    'date' => \App\Models\Setup::getStartEntryPeriod(),
                    'transaction_type_id' => 1,
                    'transaction_id' => $orderHead->id,
                    'debit' => $debit,
                    'credit' => $credit,
                    'currency_id' => $masterAccount->master_currency_id ?? 1,
                ]);

                $newIds[] = $orderHead->id;
            }
        }

        // No deletion - just update existing records
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = MasterAccount::with('account_type')->find($id);
        
        // Check if this is an AR or AP account by account_type_id
        $accountTypeId = $data->account_type_id;
        
        // Handle Account Receivable (AR) accounts - ID 4
        if ($accountTypeId == 4) {
            // Get all beginning balance invoices for AR accounts
            $beginningBalanceInvoices = InvoiceHead::where('account_id', $id)
                ->where('status', 'Beginning Balance')
                ->get();
            
            $invoiceEntries = [];
            $totalDebit = 0;
            $totalCredit = 0;
            
            foreach ($beginningBalanceInvoices as $invoice) {
                $invoiceDetail = \Modules\FinancePiutang\App\Models\InvoiceDetail::where('head_id', $invoice->id)->first();
                $invoiceEntries[] = [
                    'id' => $invoice->id,
                    'number' => $invoice->number,
                    'date' => $invoice->date_invoice,
                    'value' => $invoiceDetail->price ?? 0
                ];
                
                // Get the balance account entry for this invoice
                $balanceAccount = BalanceAccount::where('master_account_id', $id)
                    ->where('transaction_type_id', 1)
                    ->where('transaction_id', $invoice->id)
                    ->first();
                
                if ($balanceAccount) {
                    $totalDebit += $balanceAccount->debit ?? 0;
                    $totalCredit += $balanceAccount->credit ?? 0;
                }
            }
            
            $data['invoice_entries'] = $invoiceEntries;
            $data['debit'] = $totalDebit;
            $data['credit'] = $totalCredit;
        }
        // Handle Account Payable (AP) accounts - ID 8
        elseif ($accountTypeId == 8) {
            // Get all beginning balance APs for AP accounts
            $beginningBalanceAPs = OrderHead::where('account_id', $id)
                ->where('status', 'Beginning Balance')
                ->get();
            
            $apEntries = [];
            $totalDebit = 0;
            $totalCredit = 0;
            
            foreach ($beginningBalanceAPs as $ap) {
                $orderDetail = \Modules\FinancePayments\App\Models\OrderDetail::where('head_id', $ap->id)->first();
                $apEntries[] = [
                    'id' => $ap->id,
                    'number' => $ap->transaction,
                    'date' => $ap->date_order,
                    'value' => $orderDetail->price ?? 0
                ];
                
                // Get the balance account entry for this AP
                $balanceAccount = BalanceAccount::where('master_account_id', $id)
                    ->where('transaction_type_id', 1)
                    ->where('transaction_id', $ap->id)
                    ->first();
                
                if ($balanceAccount) {
                    $totalDebit += $balanceAccount->debit ?? 0;
                    $totalCredit += $balanceAccount->credit ?? 0;
                }
            }
            
            $data['ap_entries'] = $apEntries;
            $data['debit'] = $totalDebit;
            $data['credit'] = $totalCredit;
        }
        // For other account types, show both debit and credit
        else {
            // Get single beginning balance entry for non-AR/AP accounts
            $balanceAccount = BalanceAccount::where('master_account_id', $id)->where('transaction_type_id', 1)->first();
            $data['id_balance_account'] = $balanceAccount->id ?? '';
            $data['debit'] = $balanceAccount->debit ?? '';
            $data['credit'] = $balanceAccount->credit ?? '';
        }
        
        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = MasterAccount::find($id);
        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = MasterAccount::find($id);
        $account->delete();

        toast('Data Deleted Successfully!','success');
        return redirect()->back();
    }
}
