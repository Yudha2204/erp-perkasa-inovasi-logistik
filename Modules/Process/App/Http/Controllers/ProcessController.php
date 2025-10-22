<?php

namespace Modules\Process\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Modules\Process\App\Services\ExchangeRevaluationService;
use Modules\Process\App\Services\ProfitLossClosingService;
use Modules\Process\App\Services\AnnualProfitLossClosingService;
use Carbon\Carbon;

class ProcessController extends Controller
{
    protected $exchangeRevaluationService;
    protected $profitLossClosingService;
    protected $annualProfitLossClosingService;

    public function __construct(
        ExchangeRevaluationService $exchangeRevaluationService,
        ProfitLossClosingService $profitLossClosingService,
        AnnualProfitLossClosingService $annualProfitLossClosingService
    ) {
        $this->exchangeRevaluationService = $exchangeRevaluationService;
        $this->profitLossClosingService = $profitLossClosingService;
        $this->annualProfitLossClosingService = $annualProfitLossClosingService;

        $this->middleware('auth');
        $this->middleware('permission:view-revaluation@process', ['only' => ['index', 'getAvailablePeriods', 'getAvailableYears']]);
        $this->middleware('permission:execute-revaluation@process', ['only' => ['executeProcesses']]);
    }

    /**
     * Display the combined process page
     */
    public function index()
    {
        return view('process::combined.index');
    }

    /**
     * Execute multiple selected processes
     */
    public function executeProcesses(Request $request): JsonResponse
    {
        $request->validate([
            'processes' => 'required|array|min:1',
            'processes.*' => 'required|string|in:exchange_revaluation,profit_loss_closing,annual_profit_loss_closing',
            'period' => 'required_if:processes,exchange_revaluation,profit_loss_closing|date_format:Y-m',
            'year' => 'required_if:processes,annual_profit_loss_closing|date_format:Y',
            'force' => 'in:true,false,1,0,on,off'
        ]);

        $processes = $request->input('processes');
        $period = $request->input('period');
        $year = $request->input('year');
        $force = $request->boolean('force', false);
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $results = [];
        $errors = [];

        foreach ($processes as $process) {
            try {
                switch ($process) {
                    case 'exchange_revaluation':
                        if (!$force && $this->exchangeRevaluationService->isRevaluationDone($period)) {
                            $errors[] = "Exchange revaluation for period {$period} has already been done.";
                            continue 2;
                        }
                        $result = $this->exchangeRevaluationService->executeMonthlyRevaluation($period);
                        $results[] = [
                            'process' => 'Exchange Revaluation',
                            'success' => true,
                            'message' => 'Exchange revaluation completed successfully!',
                            'data' => $result
                        ];
                        break;

                    case 'profit_loss_closing':
                        if (!$force && $this->profitLossClosingService->isClosingDone($period)) {
                            $errors[] = "P&L closing for period {$period} has already been posted.";
                            continue 2;
                        }
                        $result = $this->profitLossClosingService->executeMonthlyClosing($period);
                        $results[] = [
                            'process' => 'Profit & Loss Closing',
                            'success' => (bool)($result['success'] ?? false),
                            'message' => $result['message'] ?? 'P&L closing executed.',
                            'data' => $result
                        ];
                        break;

                    case 'annual_profit_loss_closing':
                        // Check if it's January for annual process
                        if ($currentMonth !== 1) {
                            $errors[] = "Annual P&L closing can only be performed in January. Current month: {$currentMonth}";
                            continue 2;
                        }

                        // Validate that the year is not the current year
                        if ((int) $year >= $currentYear) {
                            $errors[] = "Annual closing can only be performed for previous years. Current year: {$currentYear}";
                            continue 2;
                        }

                        if (!$force && $this->annualProfitLossClosingService->isAnnualClosingDone($year)) {
                            $errors[] = "Annual P&L closing for year {$year} has already been posted.";
                            continue 2;
                        }

                        $result = $this->annualProfitLossClosingService->executeAnnualClosing($year);
                        $results[] = [
                            'process' => 'Annual Profit & Loss Closing',
                            'success' => (bool)($result['success'] ?? false),
                            'message' => $result['message'] ?? 'Annual P&L closing executed.',
                            'data' => $result
                        ];
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error in {$process}: " . $e->getMessage();
            }
        }

        $hasErrors = !empty($errors);
        $hasSuccess = !empty($results);

        return response()->json([
            'success' => $hasSuccess && !$hasErrors,
            'message' => $hasSuccess ? 'Processes executed successfully!' : 'No processes were executed.',
            'results' => $results,
            'errors' => $errors,
            'summary' => [
                'total_selected' => count($processes),
                'successful' => count($results),
                'failed' => count($errors)
            ]
        ], $hasErrors ? 400 : 200);
    }

    /**
     * Get available periods for monthly processes
     */
    public function getAvailablePeriods(): JsonResponse
    {
        $periods = [];
        
        // Generate last 12 months
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $periods[] = [
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y')
            ];
        }

        return response()->json($periods);
    }

    /**
     * Get available years for annual process
     */
    public function getAvailableYears(): JsonResponse
    {
        $years = $this->annualProfitLossClosingService->getAvailableYears();
        return response()->json($years);
    }

    /**
     * Check status of processes
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $request->validate([
            'processes' => 'required|array|min:1',
            'processes.*' => 'required|string|in:exchange_revaluation,profit_loss_closing,annual_profit_loss_closing',
            'period' => 'required_if:processes,exchange_revaluation,profit_loss_closing|date_format:Y-m',
            'year' => 'required_if:processes,annual_profit_loss_closing|date_format:Y'
        ]);

        $processes = $request->input('processes');
        $period = $request->input('period');
        $year = $request->input('year');
        $statuses = [];

        foreach ($processes as $process) {
            try {
                switch ($process) {
                    case 'exchange_revaluation':
                        $isDone = $this->exchangeRevaluationService->isRevaluationDone($period);
                        $statuses[] = [
                            'process' => 'Exchange Revaluation',
                            'is_done' => $isDone,
                            'period' => $period
                        ];
                        break;

                    case 'profit_loss_closing':
                        $isDone = $this->profitLossClosingService->isClosingDone($period);
                        $statuses[] = [
                            'process' => 'Profit & Loss Closing',
                            'is_done' => $isDone,
                            'period' => $period
                        ];
                        break;

                    case 'annual_profit_loss_closing':
                        $isDone = $this->annualProfitLossClosingService->isAnnualClosingDone($year);
                        $statuses[] = [
                            'process' => 'Annual Profit & Loss Closing',
                            'is_done' => $isDone,
                            'year' => $year
                        ];
                        break;
                }
            } catch (\Exception $e) {
                $statuses[] = [
                    'process' => ucfirst(str_replace('_', ' ', $process)),
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json($statuses);
    }

    /**
     * Display exchange revaluation page (legacy redirect)
     */
    public function exchangeRevaluation()
    {
        return redirect()->route('process.exchange-revaluation.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('process.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('process::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('process.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('process.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('process.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('process.index');
    }
}
