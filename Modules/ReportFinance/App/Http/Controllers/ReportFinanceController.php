<?php

namespace Modules\ReportFinance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\TransactionType;
use Modules\ReportFinance\App\Models\Sao;
use Modules\FinancePiutang\App\Models\InvoiceHead;
use Modules\FinancePayments\App\Models\OrderHead;
use Modules\FinancePiutang\App\Models\RecieveDetail;
use Modules\FinancePayments\App\Models\PaymentDetail;

class ReportFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-buku_besar@finance', ['only' => ['BukuBesar','BukuBesarYear','BukuBesarFilter']]);
        $this->middleware('permission:view-jurnal_umum@finance', ['only' => ['JurnalUmum','JurnalUmumYear']]);
        $this->middleware('permission:view-neraca_saldo@finance', ['only' => ['NeracaSaldo','NeracaSaldoYear','NeracaSaldoFilter']]);
        $this->middleware('permission:view-neraca@finance', ['only' => ['Neraca','NeracaYear','NeracaFilter']]);
        $this->middleware('permission:view-arus_kas@finance', ['only' => ['ArusKas','ArusKasYear','ArusKasFilter']]);
        $this->middleware('permission:view-laba_rugi@finance', ['only' => ['LabaRugi','LabaRugiYear','LabaRugiFilter']]);
        $this->middleware('permission:view-outstanding_arap@finance', ['only' => ['OutstandingARAP']]);
    }

    public function index()
    {
        $currency = MasterCurrency::all();
        return view('reportfinance::index', compact('currency'));
    }

    private function BukuBesarFilter($startDate, $endDate, $currency)
    {
        // 1) Ambil akun + baris ledger di periode, sudah DIURUTKAN
        $masterAccounts = MasterAccount::query()
            ->where('master_currency_id', $currency)
            ->whereHas('balance_accounts', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })

            // Eager load relasi (biar akses di Blade gampang & tanpa N+1)
            ->with([
                'balance_accounts' => function ($q) use ($startDate, $endDate, $currency) {
                    $q->whereBetween('date', [$startDate, $endDate])
                    ->where('currency_id', $currency)
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc');
                },
                'account_type:id,code,name,classification_id,normal_side,report_type', // sesuaikan kolom di account_types
                'account_type.classification:id,code,classification,normal_side,report_type',
            ])

            // JOIN kalau kamu mau urut/filter berdasar tabel terkait
            ->leftJoin('account_type', 'account_type.id', '=', 'master_account.account_type_id')
            ->leftJoin('classification_account_type', 'classification_account_type.id', '=', 'account_type.classification_id')
            ->select('master_account.*')
            ->get();

        // 2) Helper hitung saldo-jalan
        $calcRunning = function ($rows, $normalDebit, $opening = 0) {
        $running = $opening;
        return $rows->map(function ($r) use (&$running, $normalDebit) {
            $delta = $normalDebit
                ? ($r['total_debit'] - $r['total_kredit'])   // aset/beban
                : ($r['total_kredit'] - $r['total_debit']);  // kewajiban/ekuitas/pendapatan
            $running += $delta;
            $r['saldo'] = $running;
            return $r;
        })->values();
        };

        $groupedData = $masterAccounts->map(function ($acc) use ($startDate, $calcRunning, $currency) {
        // 3) Opening balance (sebelum periode)
        $opening = $acc->balance_accounts()
            ->where('date', '<', $startDate)
            ->where('currency_id', $currency)
            ->selectRaw('COALESCE(SUM(debit),0) as d, COALESCE(SUM(credit),0) as c')
            ->first();

        // Get normal_side from account_type only, default to 'debit'
        $normalSide = $acc->account_type?->normal_side ?? 'debit';
        
        // If normal_side is 'credit', calculate as credit - debit
        // If normal_side is 'debit', calculate as debit - credit
        $normalDebit = strtolower($normalSide) === 'debit';
        $openingVal = $normalDebit
            ? (($opening->d ?? 0) - ($opening->c ?? 0))
            : (($opening->c ?? 0) - ($opening->d ?? 0));

        // 4) Group per dokumen & sort kronologis
        $rows = collect($acc->balance_accounts)
            ->groupBy(fn($ba) => $ba->transaction_type_id.'-'.$ba->transaction_id)
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'created_at'   => $items->min('date'),
                    'transaksi_id' => $first->transaction_type ?? TransactionType::find($first->transaction_type_id),
                    'transaksi'    => $first->getTransaction(),
                    'total_debit'  => $items->sum('debit'),
                    'total_kredit' => $items->sum('credit'),
                ];
            })
            // sort utama tanggal, sekunder id gabungan biar stabil
            ->sort(function ($a, $b) {
                if ($a['created_at'] == $b['created_at']) {
                    return strcmp(
                        ($a['transaksi_id']->id ?? 0).'',
                        ($b['transaksi_id']->id ?? 0).''
                    );
                }
                return $a['created_at'] <=> $b['created_at'];
            })
            ->values();

        // 5) sisipkan baris Opening (opsional, kalau mau tampil)
        $openingRow = [
            'created_at'   => date('Y-m-d', strtotime($startDate.' -1 day')),
            'transaksi_id' => null,
            'transaksi'    => (object)['doc_no' => 'OPENING'],
            'total_debit'  => 0,
            'total_kredit' => 0,
            'saldo'        => $openingVal,
        ];

        $rowsWithRunning = $calcRunning($rows, $normalDebit, $openingVal);

        return [
            'master_account' => $acc,
            'data' => collect([$openingRow])->merge($rowsWithRunning),
        ];
        });

        return $groupedData;

    }

    public function BukuBesar(Request $request)
    {
        $currency_id = $request->currency;
        $start_datepicker = $request->start_date_buku_besar;
        $end_datepicker = $request->end_date_buku_besar;

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_datepicker){
            $startDate = date('Y-m-d', strtotime($start_datepicker));
        }
        if($end_datepicker){
            $endDate = date('Y-m-d', strtotime($end_datepicker));
        }

        $groupedData = $this->BukuBesarFilter($startDate, $endDate, $currency_id);

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::buku-besar.index', compact('groupedData', 'startDate', 'endDate', 'currency'));
    }

    public function BukuBesarYear(Request $request)
    {
        $currency_id = $request->currency;
        $year = $request->year;

        $yearBukuBesar =[];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $monthBukuBesar = $this->BukuBesarFilter($startDate, $endDate, $currency_id);
            $yearBukuBesar["month_name"][] = $startDate->format('F');
            $yearBukuBesar["data"][] = $monthBukuBesar;
        }

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::buku-besar.year', compact('yearBukuBesar', 'startDate', 'endDate', 'currency', 'year'));
    }

    public function JurnalUmum(Request $request)
    {
        // $currency = $request->currency;
        $start_picker = $request->start_date_jurnal;
        $end_picker = $request->end_date_jurnal;

        $query = BalanceAccount::query()
            ->with('master_account', 'transaction_type');

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_picker){
            $startDate = date('Y-m-d', strtotime($start_picker));
        }
        if($end_picker){
            $endDate = date('Y-m-d', strtotime($end_picker));
        }

        $query->whereBetween('date', [$startDate, $endDate]);

        // if ($currency) {
        //     $query->whereHas('master_account', function ($query) use ($currency) {
        //         $query->where('master_currency_id', $currency);
        //     });
        // }

        $balanceAccountData = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        $groupedByTransaction = $balanceAccountData->groupBy(function($item) {
            return $item->transaction_type_id . '-' . $item->transaction_id;
        });

        $groupedData = [];
        $idrCurrencyId = MasterCurrency::where('initial', 'IDR')->first()->id ?? 1;

        foreach($groupedByTransaction as $key => $entries) {
            // dd(explode('-',$key)[0]);
            if(in_array(explode('-',$key)[0],[3,7])){
                continue;
            }
            $head = $entries->first()->getTransaction();
            if ($head) {
                $headCurrencyId = $head->currency_id ?? $entries->first()->currency_id;

                $groupedData[explode('-',$key)[0]][] = [
                    'head' => $head,
                    'jurnal' => $entries->where('currency_id', $headCurrencyId)->values(),
                    'jurnalIDR' => $entries->where('currency_id', $idrCurrencyId)->values(),
                ];
            }
        }
        // dd($groupedData);
        return view('reportfinance::jurnal-umum.index', compact('groupedData','startDate','endDate'));
    }

    public function JurnalUmumYear(Request $request)
    {
        $currency = $request->currency;
        $year = $request->year;

        $query = BalanceAccount::query()
            ->with('master_account', 'transaction_type');

        $startDate = date('Y-m-d', strtotime("first day of January $year"));
        $endDate = date('Y-m-d', strtotime("last day of December $year"));

        $query->whereBetween('date', [$startDate, $endDate]);

        if ($currency) {
            $query->whereHas('master_account', function ($query) use ($currency) {
                $query->where('master_currency_id', $currency);
            });
        }

        $balanceAccountData = $query->get();

        $groupedData = [];

        foreach ($balanceAccountData as $data) {
            $month = Carbon::parse($data->date)->format('F');
            $groupedData[$month][$data->transaction_type_id][$data->transaction_id] = $data->getTransaction();
        }

        $currency = MasterCurrency::find($currency);

        return view('reportfinance::jurnal-umum.year', compact('groupedData','startDate','endDate','currency', 'year'));
    }


    public function ArusKas(Request $request)
    {
        $currency_id = $request->currency;
        $start_datepicker = $request->start_date_arus_kas;
        $end_datepicker = $request->end_date_arus_kas;

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_datepicker){
            $startDate = date('Y-m-d', strtotime($start_datepicker));
        }
        if($end_datepicker){
            $endDate = date('Y-m-d', strtotime($end_datepicker));
        }

        $account = $this->ArusKasFilter($startDate, $endDate, $currency_id);

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::arus-kas.index', compact("currency" ,'account','startDate','endDate'));
    }

    public function ArusKasYear(Request $request)
    {
        $currency_id = $request->currency;
        $year = $request->year;
        $yearCashFlow=[];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $monthCashFlow = $this->ArusKasFilter($startDate, $endDate, $currency_id);
            $yearCashFlow["month_name"][] = $startDate->format('F');
            $yearCashFlow["data"][] = $monthCashFlow;
        }

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::arus-kas.year', compact("currency" ,'yearCashFlow','year'));
    }

    private function ArusKasFilter($startDate, $endDate, $currency)
    {
        $account = AccountType::whereHas('master_accounts', function ($query) use ($currency, $startDate, $endDate) {
            $query->where('master_currency_id', $currency)
                  ->whereHas('balance_accounts', function ($query) use ($startDate, $endDate) {
                      $query->whereBetween('date', [$startDate, $endDate]);
                  });
        })->with(['master_accounts' => function ($query) use ($currency, $startDate, $endDate) {
            $query->where('master_currency_id', $currency)
                  ->with(['balance_accounts' => function ($query) use ($startDate, $endDate) {
                      $query->whereBetween('date', [$startDate, $endDate]);
                  }]);
        }])->get();

        $account = $account->groupBy('cash_flow_name');

        return $account;
    }

    public function LabaRugi(Request $request)
    {
        $currency_id = $request->currency;
        $start_datepicker = $request->start_date_profit_loss;
        $end_datepicker = $request->end_date_profit_loss;

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_datepicker){
            $startDate = date('Y-m-d', strtotime($start_datepicker));
        }
        if($end_datepicker){
            $endDate = date('Y-m-d', strtotime($end_datepicker));
        }

        $labaRugi = $this->LabaRugiFilter($startDate, $endDate, $currency_id);

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::laba-rugi.spesific', compact('currency','startDate','endDate','labaRugi'));
    }

    public function LabaRugiYear(Request $request)
    {
        $currency_id = $request->currency;
        $year = $request->year;
        $yearProfitLoss=[];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $monthProfitLoss = $this->LabaRugiFilter($startDate, $endDate, $currency_id);
            $yearProfitLoss["month_name"][] = $startDate->format('F');
            $yearProfitLoss["data"][] = $monthProfitLoss;
        }

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::laba-rugi.year', compact('currency', 'yearProfitLoss', 'year'));
    }

    private function LabaRugiFilter($startDate, $endDate, $currency_id)
    {
        $baseQuery = function($code) use ($currency_id, $startDate, $endDate) {
            return AccountType::whereHas('master_accounts', function ($query) use ($currency_id, $startDate, $endDate) {
                $query
                    ->whereHas('balance_accounts', function ($query) use ($startDate, $endDate, $currency_id) {
                        $query->whereBetween('date', [$startDate, $endDate])
                        ->where('currency_id', $currency_id);
                    });
            })->with(['master_accounts' => function ($query) use ($currency_id, $startDate, $endDate) {
                $query->with(['balance_accounts' => function ($query) use ($startDate, $endDate, $currency_id) {
                        $query->whereBetween('date', [$startDate, $endDate])
                        ->where('currency_id', $currency_id);
                    }]);
            }])
            ->where('code', $code)
            ->get();
        };

        $income = $baseQuery('4-0000');
        $sales_discount = $baseQuery('4-0001');
        $cost_of_sale = $baseQuery('5-0000');
        $purchase_discount = $baseQuery('5-0001');
        $expense = $baseQuery('6-0000');
        $other_income = $baseQuery('7-0000');
        $other_expense = $baseQuery('8-0000');
        $rounding_difference = $baseQuery('9-0001');
        $exchange_profit_loss = $baseQuery('9-0002');

        return [
            "income" => $income,
            "sales_discount" => $sales_discount,
            "cost_of_sale" => $cost_of_sale,
            "purchase_discount" => $purchase_discount,
            "expense" => $expense,
            "other_income" => $other_income,
            "other_expense" => $other_expense,
            "rounding_difference" => $rounding_difference,
            "exchange_profit_loss" => $exchange_profit_loss
        ];
    }

    public function NeracaSaldo(Request $request)
    {
        $foreign_currency = $request->has('foreign_currency') && $request->foreign_currency == '1';
        $start_datepicker = $request->start_date_neraca;
        $end_datepicker = $request->end_date_neraca;
        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_datepicker){
            $startDate = date('Y-m-d', strtotime($start_datepicker));
        }
        if($end_datepicker){
            $endDate = date('Y-m-d', strtotime($end_datepicker));
        }

        // Always use IDR currency
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }

        $filter = $this->NeracaSaldoFilter($startDate, $endDate, $idrCurrency->id, $foreign_currency);
        $masterAccounts = $filter["masterAccounts"];
        $footer = $filter["footer"];

        return view('reportfinance::neraca-saldo.spesific', compact('startDate', 'endDate','masterAccounts', 'footer', 'foreign_currency', 'idrCurrency'));
    }

    private function NeracaSaldoFilter($startDate, $endDate, $currency, $foreign_currency = false)
    {
        // Get all accounts that have balance_accounts in IDR (currency passed is IDR)
        $masterAccounts = MasterAccount::whereHas('balance_accounts', function ($query) use ($startDate, $endDate, $currency) {
            $query->whereBetween('date', [$startDate, $endDate])
                  ->where('currency_id', $currency);
        })
        ->with(['balance_accounts' => function ($query) use ($startDate, $endDate, $currency) {
            $query->whereBetween('date', [$startDate, $endDate])
                  ->where('currency_id', $currency);
        }, 'currency'])
        ->get();

        $footer = [
            'saldoAwalDebit' => 0,
            'saldoAwalKredit' => 0,
            'mutasDebit' => 0,
            'mutasKredit' => 0,
            'netMutasi' => 0,
            'saldoAkhirDebit' => 0,
            'saldoAkhirKredit' => 0,
        ];

        // Get IDR currency for comparison
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();

        foreach ($masterAccounts as $ma ) {
            // Get IDR data
            $saldoAwal = $ma->getDebitKreditSaldoAwal($currency);
            $footer['saldoAwalDebit'] += $saldoAwal["debit"];
            $footer['saldoAwalKredit'] += $saldoAwal["kredit"];

            $data = $ma->getDebitKreditAll($startDate, $endDate, $currency);
            $footer['mutasDebit'] += $data["debit"];
            $footer['mutasKredit'] += $data["kredit"];

            $netMutation = $ma->getNetMutation($startDate, $endDate, $currency);
            $footer['netMutasi'] += $netMutation;

            if ($netMutation < 0) {
                $footer['saldoAkhirDebit'] += $netMutation;
            } else {
                $footer['saldoAkhirKredit'] += $netMutation;
            }

            // If foreign currency is checked and account is not IDR, get foreign currency data
            if ($foreign_currency && $ma->master_currency_id != $idrCurrency->id) {
                // Check if account has balance_accounts in foreign currency
                $hasForeignCurrencyData = BalanceAccount::where('master_account_id', $ma->id)
                    ->where('currency_id', $ma->master_currency_id)
                    ->where(function($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate])
                          ->orWhere('transaction_type_id', 1); // Include opening balance
                    })
                    ->exists();
                
                if ($hasForeignCurrencyData) {
                    $ma->foreign_currency_data = [
                        'currency' => $ma->currency,
                        'saldoAwal' => $ma->getDebitKreditSaldoAwal($ma->master_currency_id),
                        'data' => $ma->getDebitKreditAll($startDate, $endDate, $ma->master_currency_id),
                        'netMutation' => $ma->getNetMutation($startDate, $endDate, $ma->master_currency_id),
                    ];
                }
            }
        }

        return ["masterAccounts"=>$masterAccounts, "footer"=>$footer];
    }

    public function NeracaSaldoYear(Request $request)
    {
        $foreign_currency = $request->has('foreign_currency') && $request->foreign_currency == '1';
        $year = $request->year;
        
        // Always use IDR currency
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }

        $yearTrialBalance=[];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $monthTrialBalance = $this->NeracaSaldoFilter($startDate, $endDate, $idrCurrency->id, $foreign_currency);
            $yearTrialBalance["month_name"][] = $startDate->format('F');
            $yearTrialBalance["data"][] = $monthTrialBalance["masterAccounts"];
            $yearTrialBalance["total"][] = $monthTrialBalance["footer"];
        }

        return view('reportfinance::neraca-saldo.year', compact('idrCurrency', 'yearTrialBalance', 'year', 'foreign_currency'));
    }

    public function Neraca(Request $request)
    {
        $currency_id = $request->currency;
        $start_datepicker = $request->start_date_neraca_balance;
        $end_datepicker = $request->end_date_neraca_balance;

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_datepicker){
            $startDate = date('Y-m-d', strtotime($start_datepicker));
        }
        if($end_datepicker){
            $endDate = date('Y-m-d', strtotime($end_datepicker));
        }

        // Always use IDR currency
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }

        $balanceSheet = $this->NeracaFilter($startDate, $endDate, $idrCurrency->id);

        return view('reportfinance::neraca.spesific', compact('startDate', 'endDate', 'balanceSheet', 'idrCurrency'));
    }

    public function NeracaYear(Request $request)
    {
        $currency_id = $request->currency;
        $year = $request->year;

        // Always use IDR currency
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }

        $yearBalanceSheet = [];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $monthBalanceSheet = $this->NeracaFilter($startDate, $endDate, $idrCurrency->id);
            $yearBalanceSheet["month_name"][] = $startDate->format('F');
            $yearBalanceSheet["data"][] = $monthBalanceSheet;
        }

        return view('reportfinance::neraca.year', compact('idrCurrency', 'yearBalanceSheet', 'year'));
    }

    private function NeracaFilter($startDate, $endDate, $currency)
    {
        // Define classification codes for Aktiva and Passiva
        $aktivaCodes = ['1-0001', '1-0002', '1-0003', '1-0004', '1-0005', '1-0006', '1-0007']; // Cash, Bank, Current Asset, Account Receivable, Fixed Asset, Other Asset, Accumulated Depreciation
        $passivaCodes = ['2-0001', '2-0002', '2-0003', '2-0004', '3-0001', '3-0002', '3-0003']; // Account Payable, Other Payable, Prepaid Sales, Tax Payable, Equity, Current Earning, Retained Earning

        // Get account types for balance sheet
        $aktivaAccountTypes = AccountType::whereIn('code', $aktivaCodes)
            ->orderBy('code')
            ->get();

        $passivaAccountTypes = AccountType::whereIn('code', $passivaCodes)
            ->orderBy('code')
            ->get();

        // Process Aktiva
        // Step 1: Get all detail accounts with aktiva account types that have balances
        $aktivaAccountTypeIds = $aktivaAccountTypes->pluck('id')->toArray();
        $aktivaDetailAccounts = MasterAccount::whereIn('account_type_id', $aktivaAccountTypeIds)
            ->where('type', 'detail')
            ->with(['account_type'])
            ->orderBy('code')
            ->get()
            ->filter(function($detail) use ($endDate, $currency) {
                // Check if account has IDR balance or foreign currency balance
                $hasIDRBalance = BalanceAccount::where('master_account_id', $detail->id)
                    ->where('date', '<=', $endDate)
                    ->where('currency_id', $currency)
                    ->exists();
                
                $hasForeignBalance = BalanceAccount::where('master_account_id', $detail->id)
                    ->where('date', '<=', $endDate)
                    ->where('currency_id', $detail->master_currency_id)
                    ->exists();
                
                return $hasIDRBalance || $hasForeignBalance;
            });

        // Step 2: Group detail accounts by parent (header)
        $aktivaGroupedByParent = $aktivaDetailAccounts->groupBy(function($item) {
            return $item->parent;
        });

        // Step 3: Process each group
        $aktivaData = [];
        $aktivaTotal = 0;
        foreach ($aktivaGroupedByParent as $parentId => $children) {
            $header = null;
            $headerBalance = 0;
            
            // If parent exists, get the header account (header might not have account_type_id)
            if ($parentId !== null) {
                $header = MasterAccount::where('id', $parentId)
                    ->where('type', 'header')
                    ->with(['account_type'])
                    ->first();
                
                if ($header) {
                    // Check if any child is Accumulated Depreciation (1-0007)
                    $isNegative = $children->contains(function($child) {
                        return $child->account_type && $child->account_type->code === '1-0007';
                    });
                    $headerBalance = $this->calculateAccountBalance($header, $startDate, $endDate, $currency, $isNegative);
                }
            }

            $childrenData = [];
            $childrenTotal = $headerBalance;
            
            foreach ($children as $child) {
                $isNegative = $child->account_type && $child->account_type->code === '1-0007';
                $childBalance = $this->calculateAccountBalance($child, $startDate, $endDate, $currency, $isNegative);
                
                // Get foreign currency data if account is not IDR
                $foreignCurrencyData = null;
                if ($child->master_currency_id != $currency) {
                    $foreignBalance = $this->calculateAccountBalance($child, $startDate, $endDate, $child->master_currency_id, $isNegative);
                    if ($foreignBalance != 0) {
                        $foreignCurrencyData = [
                            'currency' => $child->currency,
                            'balance' => $foreignBalance,
                        ];
                        // If no IDR balance but has foreign balance, use foreign balance for total calculation
                        if ($childBalance == 0) {
                            // Convert foreign balance to IDR equivalent (already done in balance_account_data)
                            // Just use the IDR balance which should exist
                            $childBalance = $this->calculateAccountBalance($child, $startDate, $endDate, $currency, $isNegative);
                        }
                    }
                }
                
                $childrenTotal += $childBalance;
                $childrenData[] = [
                    'account' => $child,
                    'balance' => $childBalance,
                    'foreign_currency' => $foreignCurrencyData,
                ];
            }

            $aktivaData[] = [
                'header' => $header,
                'header_balance' => $headerBalance,
                'children' => $childrenData,
                'total' => $childrenTotal,
            ];
            $aktivaTotal += $childrenTotal;
        }

        // Process Passiva
        // Step 1: Get all detail accounts with passiva account types that have balances
        $passivaAccountTypeIds = $passivaAccountTypes->pluck('id')->toArray();
        $passivaDetailAccounts = MasterAccount::whereIn('account_type_id', $passivaAccountTypeIds)
            ->where('type', 'detail')
            ->with(['account_type'])
            ->orderBy('code')
            ->get()
            ->filter(function($detail) use ($endDate, $currency) {
                // Check if account has IDR balance or foreign currency balance
                $hasIDRBalance = BalanceAccount::where('master_account_id', $detail->id)
                    ->where('date', '<=', $endDate)
                    ->where('currency_id', $currency)
                    ->exists();
                
                $hasForeignBalance = BalanceAccount::where('master_account_id', $detail->id)
                    ->where('date', '<=', $endDate)
                    ->where('currency_id', $detail->master_currency_id)
                    ->exists();
                
                return $hasIDRBalance || $hasForeignBalance;
            });

        // Step 2: Group detail accounts by parent (header)
        $passivaGroupedByParent = $passivaDetailAccounts->groupBy(function($item) {
            return $item->parent;
        });

        // Step 3: Process each group
        $passivaData = [];
        $passivaTotal = 0;
        foreach ($passivaGroupedByParent as $parentId => $children) {
            $header = null;
            $headerBalance = 0;
            
            // If parent exists, get the header account (header might not have account_type_id)
            if ($parentId !== null) {
                $header = MasterAccount::where('id', $parentId)
                    ->where('type', 'header')
                    ->with(['account_type'])
                    ->first();
                
                if ($header) {
                    $headerBalance = $this->calculateAccountBalance($header, $startDate, $endDate, $currency, false);
                }
            }

            $childrenData = [];
            $childrenTotal = $headerBalance;
            
            foreach ($children as $child) {
                $childBalance = $this->calculateAccountBalance($child, $startDate, $endDate, $currency, false);
                
                // Get foreign currency data if account is not IDR
                $foreignCurrencyData = null;
                if ($child->master_currency_id != $currency) {
                    $foreignBalance = $this->calculateAccountBalance($child, $startDate, $endDate, $child->master_currency_id, false);
                    if ($foreignBalance != 0) {
                        $foreignCurrencyData = [
                            'currency' => $child->currency,
                            'balance' => $foreignBalance,
                        ];
                        // If no IDR balance but has foreign balance, use foreign balance for total calculation
                        if ($childBalance == 0) {
                            // Convert foreign balance to IDR equivalent (already done in balance_account_data)
                            // Just use the IDR balance which should exist
                            $childBalance = $this->calculateAccountBalance($child, $startDate, $endDate, $currency, false);
                        }
                    }
                }
                
                $childrenTotal += $childBalance;
                $childrenData[] = [
                    'account' => $child,
                    'balance' => $childBalance,
                    'foreign_currency' => $foreignCurrencyData,
                ];
            }

            $passivaData[] = [
                'header' => $header,
                'header_balance' => $headerBalance,
                'children' => $childrenData,
                'total' => $childrenTotal,
            ];
            $passivaTotal += $childrenTotal;
        }

        return [
            'aktiva' => [
                'data' => $aktivaData,
                'total' => $aktivaTotal,
            ],
            'passiva' => [
                'data' => $passivaData,
                'total' => $passivaTotal,
            ],
        ];
    }

    private function calculateAccountBalance($account, $startDate, $endDate, $currency, $isNegative = false)
    {
        // Get all balance accounts up to end date
        $allBalances = BalanceAccount::where('master_account_id', $account->id)
            ->where('currency_id', $currency)
            ->where('date', '<=', $endDate)
            ->get();
        
        // Get normal side
        $normalSide = $account->account_type?->normal_side ?? 'debit';
        $normalDebit = strtolower($normalSide) === 'debit';
        
        // Calculate total debit and credit
        $totalDebit = $allBalances->sum('debit');
        $totalCredit = $allBalances->sum('credit');
        
        // Calculate balance based on normal side
        $balance = $normalDebit
            ? ($totalDebit - $totalCredit)
            : ($totalCredit - $totalDebit);
        
        // For Accumulated Depreciation, make it negative
        if ($isNegative) {
            $balance = -abs($balance);
        }
        
        return $balance;
    }

    public function LaporanKeuangan(Request $request)
    {
        $source = $request->source;
        $start_datepicker = $request->start_date_laporan_rekening;
        $end_datepicker = $request->end_date_laporan_rekening;

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if($start_datepicker){
            $startDate = date('Y-m-d', strtotime($start_datepicker));
        }
        if($end_datepicker){
            $endDate = date('Y-m-d', strtotime($end_datepicker));
        }

        $query = Sao::with(['contact', 'currency'])
                        ->where('isPaid', false)
                        ->where('type', $source)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('contact', function ($subQuery) use ($request) {
                $subQuery->where('customer_name', 'LIKE', '%' . $request->search . '%');
            });
        }
        $sao = [];
        if($query){
            $sao = $query->paginate(10);
        }
        return view('reportfinance::laporan-rekening.index', compact('sao'));
    }

    public function OutstandingARAP(Request $request)
    {
        $source = $request->source;
        $as_of_date = $request->as_of_date;

        // Default to today if no date provided
        $asOfDate = Carbon::now()->endOfDay();
        if($as_of_date){
            $asOfDate = Carbon::parse($as_of_date)->endOfDay();
        }

        $outstandingData = collect();

        if($source == 'invoice') {
            // AR (Invoice) - Calculate outstanding from InvoiceHead and RecieveDetail
            $invoices = InvoiceHead::with(['contact', 'currency'])
                ->where('date_invoice', '<=', $asOfDate->format('Y-m-d'))
                ->orderBy('date_invoice', 'desc');

            if ($request->has('search') && $request->search != '') {
                $invoices->whereHas('contact', function ($subQuery) use ($request) {
                    $subQuery->where('customer_name', 'LIKE', '%' . $request->search . '%');
                });
            }

            $invoices = $invoices->get();

            foreach($invoices as $invoice) {
                // Get all payments received up to as_of_date
                $paymentsReceived = RecieveDetail::where('invoice_id', $invoice->id)
                    ->where('charge_type', 'invoice')
                    ->whereHas('head', function($query) use ($asOfDate) {
                        $query->where('date_recieve', '<=', $asOfDate->format('Y-m-d'));
                    })
                    ->get();

                $alreadyPaid = $paymentsReceived->sum('total') + $paymentsReceived->sum('dp');
                $invoiceTotal = $invoice->total;
                $remaining = $invoiceTotal - $alreadyPaid;

                // Only include if there's outstanding amount
                if($remaining > 0) {
                    $outstandingData->push((object)[
                        'id' => $invoice->id,
                        'contact' => $invoice->contact,
                        'currency' => $invoice->currency,
                        'invoice' => $invoice,
                        'order' => null,
                        'date' => $invoice->date_invoice,
                        'account' => 'piutang',
                        'total' => $invoiceTotal,
                        'already_paid' => $alreadyPaid,
                        'remaining' => $remaining,
                        'isPaid' => false,
                    ]);
                }
            }
        } elseif($source == 'order') {
            // AP - Calculate outstanding from OrderHead and PaymentDetail
            $orders = OrderHead::with(['vendor', 'currency'])
                ->where('date_order', '<=', $asOfDate->format('Y-m-d'))
                ->orderBy('date_order', 'desc');

            if ($request->has('search') && $request->search != '') {
                $orders->whereHas('vendor', function ($subQuery) use ($request) {
                    $subQuery->where('customer_name', 'LIKE', '%' . $request->search . '%');
                });
            }

            $orders = $orders->get();

            foreach($orders as $order) {
                // Get all payments made up to as_of_date
                $paymentsMade = PaymentDetail::where('payable_id', $order->id)
                    ->where('charge_type', 'payable')
                    ->whereHas('head', function($query) use ($asOfDate) {
                        $query->where('date_payment', '<=', $asOfDate->format('Y-m-d'));
                    })
                    ->get();

                $alreadyPaid = $paymentsMade->sum('total') + $paymentsMade->sum('dp');
                $orderTotal = $order->total;
                $remaining = $orderTotal - $alreadyPaid;

                // Only include if there's outstanding amount
                if($remaining > 0) {
                    $outstandingData->push((object)[
                        'id' => $order->id,
                        'contact' => $order->vendor,
                        'currency' => $order->currency,
                        'invoice' => null,
                        'order' => $order,
                        'date' => $order->date_order,
                        'account' => 'hutang',
                        'total' => $orderTotal,
                        'already_paid' => $alreadyPaid,
                        'remaining' => $remaining,
                        'isPaid' => false,
                    ]);
                }
            }
        }

        // Calculate totals by currency for footer
        $totalsByCurrency = $outstandingData->groupBy(function($item) {
            return $item->currency->id ?? 0;
        })->map(function($group) {
            return [
                'currency' => $group->first()->currency ?? null,
                'total' => $group->sum('remaining')
            ];
        });

        // Paginate manually
        $page = $request->get('page', 1);
        $perPage = 10;
        $items = $outstandingData->slice(($page - 1) * $perPage, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $outstandingData->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $currency = MasterCurrency::all();

        return view('reportfinance::outstanding-arap.index', compact('paginator', 'asOfDate', 'source', 'currency', 'totalsByCurrency'));
    }
    public function PrintLaporanKeuangan($id)
    {
        $sao = Sao::with(['contact', 'currency'])->findOrFail($id);
        $relatedSaos = [];
        if($sao->account == 'piutang'){
            $relatedSaos = Sao::where('contact_id', $sao->contact_id)
                                ->where('account', 'piutang')
                                ->where('isPaid', false)
                                ->get();
        }else{
            $relatedSaos = Sao::where('contact_id', $sao->contact_id)
                                ->where('account', 'hutang')
                                ->where('isPaid', false)
                                ->get();
        }

        return view('reportfinance::laporan-rekening.pdf', compact('relatedSaos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reportfinance::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('reportfinance::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('reportfinance::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        // Log::info("destroy");
    }
}
