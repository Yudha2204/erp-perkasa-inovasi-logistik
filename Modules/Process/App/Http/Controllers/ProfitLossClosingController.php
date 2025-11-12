<?php

namespace Modules\Process\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Process\App\Services\ProfitLossClosingService;
use Modules\FinanceDataMaster\App\Models\FiscalPeriod;
use Carbon\Carbon;

class ProfitLossClosingController extends Controller
{
    protected ProfitLossClosingService $profitLossClosingService;

    public function __construct(ProfitLossClosingService $profitLossClosingService)
    {
        $this->profitLossClosingService = $profitLossClosingService;

        $this->middleware('auth');
        // You may need to adjust these permissions to match your seeder/config
        $this->middleware('permission:view-pl-closing@process', ['only' => ['index','status']]);
        $this->middleware('permission:execute-pl-closing@process', ['only' => ['execute']]);
    }

    /**
     * Display the Profit & Loss closing page
     */
    public function index()
    {
        return view('process::profit-loss-closing.index');
    }

    /**
     * Execute Profit & Loss monthly closing
     */
    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m',
            'force'  => 'in:true,false,1,0,on,off'
        ]);

        $period = $request->input('period');
        $force = $request->boolean('force', false);

        try {
            // Check if period is closed
            $fiscalPeriod = FiscalPeriod::where('period', $period)->first();
            if ($fiscalPeriod && $fiscalPeriod->isClosed()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot execute P&L closing: Fiscal period {$period} is closed. Please open the period first.",
                ], 400);
            }

            if (!$force && $this->profitLossClosingService->isClosingDone($period)) {
                return response()->json([
                    'success' => false,
                    'message' => "P&L closing for period {$period} has already been posted.",
                    'already_done' => true
                ], 400);
            }

            $result = $this->profitLossClosingService->executeMonthlyClosing($period);

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'P&L closing executed.',
                'data' => $result
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during P&L closing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get closing status for a period
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m'
        ]);

        $period = $request->input('period');
        $isDone = $this->profitLossClosingService->isClosingDone($period);

        return response()->json([
            'period' => $period,
            'is_done' => $isDone
        ]);
    }

    /**
     * Get available periods for closing
     * Fetches periods from fiscal_periods table
     */
    public function getAvailablePeriods(): JsonResponse
    {
        // Fetch periods from fiscal_periods table, ordered by period descending
        $fiscalPeriods = \Modules\FinanceDataMaster\App\Models\FiscalPeriod::orderBy('period', 'desc')
            ->limit(24) // Limit to last 24 periods
            ->get();

        $periods = [];

        if ($fiscalPeriods->isEmpty()) {
            // If no fiscal periods exist, generate last 12 months as fallback
            for ($i = 0; $i < 12; $i++) {
                $date = Carbon::now()->subMonths($i);
                $periods[] = [
                    'value' => $date->format('Y-m'),
                    'label' => $date->format('F Y')
                ];
            }
        } else {
            // Format fiscal periods for dropdown
            foreach ($fiscalPeriods as $fiscalPeriod) {
                $date = Carbon::createFromFormat('Y-m', $fiscalPeriod->period);
                $periods[] = [
                    'value' => $fiscalPeriod->period,
                    'label' => $date->format('F Y') . ($fiscalPeriod->status === 'closed' ? ' (Closed)' : '')
                ];
            }
        }

        return response()->json($periods);
    }
}


