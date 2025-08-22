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
        $this->middleware('permission:view-arus_kas@finance', ['only' => ['ArusKas','ArusKasYear','ArusKasFilter']]);
        $this->middleware('permission:view-laba_rugi@finance', ['only' => ['LabaRugi','LabaRugiYear','LabaRugiFilter']]);
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
                'balance_accounts' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate])
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

        $groupedData = $masterAccounts->map(function ($acc) use ($startDate, $calcRunning) {
        // 3) Opening balance (sebelum periode)
        $opening = $acc->balance_accounts()
            ->where('date', '<', $startDate)
            ->selectRaw('COALESCE(SUM(debit),0) as d, COALESCE(SUM(credit),0) as c')
            ->first();

        $normalDebit = in_array(strtolower($acc->normal_side ?? 'debit'), ['debit']);
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
        $currency = $request->currency;
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

        if ($currency) {
            $query->whereHas('master_account', function ($query) use ($currency) {
                $query->where('master_currency_id', $currency);
            });
        }

        $balanceAccountData = $query->get();

        $groupedData = [];

        foreach ($balanceAccountData as $data) {
            $groupedData[$data->transaction_type_id][$data->transaction_id] = $data->getTransaction();
        }

        $currency = MasterCurrency::find($currency);

        return view('reportfinance::jurnal-umum.index', compact('groupedData','startDate','endDate','currency'));
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
        $pendapatan = $labaRugi["pendapatan"];
        $beban = $labaRugi["beban"];
        $beban_operasional = $labaRugi["beban_operasional"];

        $currency = MasterCurrency::find($currency_id);


        return view('reportfinance::laba-rugi.spesific', compact('currency','startDate','endDate','pendapatan','beban','beban_operasional'));
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
            $yearProfitLoss["data"][] = [
               "pendapatan" => $monthProfitLoss["pendapatan"],
               "beban" => $monthProfitLoss["beban"],
               "beban_operasional" => $monthProfitLoss["beban_operasional"]
            ];
        }

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::laba-rugi.year', compact('currency', 'yearProfitLoss', 'year'));
    }

    private function LabaRugiFilter($startDate, $endDate, $currency_id)
    {
        $pendapatan = AccountType::whereHas('master_accounts', function ($query) use ($currency_id, $startDate, $endDate) {
            $query->where('master_currency_id', $currency_id)
                ->whereHas('balance_accounts', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                });
        })->with(['master_accounts' => function ($query) use ($currency_id, $startDate, $endDate) {
            $query->where('master_currency_id', $currency_id)
                ->with(['balance_accounts' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }]);
        }])
        ->where('code','like','4%')
        ->get();

        $beban = AccountType::whereHas('master_accounts', function ($query) use ($currency_id, $startDate, $endDate) {
                $query->where('master_currency_id', $currency_id)
                    ->whereHas('balance_accounts', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
            })->with(['master_accounts' => function ($query) use ($currency_id, $startDate, $endDate) {
                $query->where('master_currency_id', $currency_id)
                    ->with(['balance_accounts' => function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    }]);
            }])
            ->where('code','like','5%')
            ->get();

        $beban_operasional = AccountType::whereHas('master_accounts', function ($query) use ($currency_id, $startDate, $endDate) {
                $query->where('master_currency_id', $currency_id)
                    ->whereHas('balance_accounts', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    });
            })->with(['master_accounts' => function ($query) use ($currency_id, $startDate, $endDate) {
                $query->where('master_currency_id', $currency_id)
                    ->with(['balance_accounts' => function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('date', [$startDate, $endDate]);
                    }]);
            }])
            ->where('code','like','6%')
            ->get();

        return [
            "pendapatan" => $pendapatan,
            "beban" => $beban,
            "beban_operasional" => $beban_operasional
        ];
    }

    public function NeracaSaldo(Request $request)
    {
        $currency_id = $request->currency;
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

        $filter = $this->NeracaSaldoFilter($startDate, $endDate, $currency_id);
        $masterAccounts = $filter["masterAccounts"];
        $footer = $filter["footer"];
        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::neraca-saldo.spesific', compact('startDate', 'endDate','currency','masterAccounts', 'footer'));
    }

    private function NeracaSaldoFilter($startDate, $endDate, $currency)
    {
        $masterAccounts = MasterAccount::whereHas('balance_accounts', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })
        ->with(['balance_accounts' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])
        ->where('master_currency_id', $currency)
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
        foreach ($masterAccounts as $ma ) {
            $saldoAwal = $ma->getDebitKreditSaldoAwal();
            $footer['saldoAwalDebit'] += $saldoAwal["debit"];
            $footer['saldoAwalKredit'] += $saldoAwal["kredit"];

            $data = $ma->getDebitKreditAll($startDate, $endDate);
            $footer['mutasDebit'] += $data["debit"];
            $footer['mutasKredit'] += $data["kredit"];

            $netMutation = $ma->getNetMutation($startDate, $endDate);
            $footer['netMutasi'] += $netMutation;

            if ($netMutation < 0) {
                $footer['saldoAkhirDebit'] += $netMutation;

            } else {
                $footer['saldoAkhirKredit'] += $netMutation;
            }
        }

        return ["masterAccounts"=>$masterAccounts, "footer"=>$footer];
    }

    public function NeracaSaldoYear(Request $request)
    {
        $currency_id = $request->currency;
        $year = $request->year;
        $yearTrialBalance=[];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $monthTrialBalance = $this->NeracaSaldoFilter($startDate, $endDate, $currency_id);
            $yearTrialBalance["month_name"][] = $startDate->format('F');
            $yearTrialBalance["data"][] = $monthTrialBalance["masterAccounts"];
            $yearTrialBalance["total"][] = $monthTrialBalance["footer"];
        }

        $currency = MasterCurrency::find($currency_id);

        return view('reportfinance::neraca-saldo.year', compact('currency', 'yearTrialBalance', 'year'));
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
