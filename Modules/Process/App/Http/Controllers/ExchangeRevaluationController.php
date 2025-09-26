<?php

namespace Modules\Process\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Process\App\Services\ExchangeRevaluationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ExchangeRevaluationController extends Controller
{
    protected $exchangeRevaluationService;

    public function __construct(ExchangeRevaluationService $exchangeRevaluationService)
    {
        $this->exchangeRevaluationService = $exchangeRevaluationService;

        $this->middleware('auth');
        $this->middleware('permission:view-revaluation@process', ['only' => ['index','status']]);
        $this->middleware('permission:execute-revaluation@process', ['only' => ['execute']]);
    }

    /**
     * Display the revaluation form
     */
    public function index()
    {
        return view('process::exchange-revaluation.index');
    }

    /**
     * Execute exchange revaluation
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
            // Check if revaluation already done
            if (!$force && $this->exchangeRevaluationService->isRevaluationDone($period)) {
                return response()->json([
                    'success' => false,
                    'message' => "Revaluation for period {$period} has already been done.",
                    'already_done' => true
                ], 400);
            }

            // Execute revaluation
            $result = $this->exchangeRevaluationService->executeMonthlyRevaluation($period);

            return response()->json([
                'success' => true,
                'message' => 'Exchange revaluation completed successfully!',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during revaluation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revaluation status for a period
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m'
        ]);

        $period = $request->input('period');
        $isDone = $this->exchangeRevaluationService->isRevaluationDone($period);

        return response()->json([
            'period' => $period,
            'is_done' => $isDone
        ]);
    }

    /**
     * Get available periods for revaluation
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
}
