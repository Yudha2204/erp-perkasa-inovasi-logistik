<?php

namespace Modules\Process\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Process\App\Services\AnnualProfitLossClosingService;
use Carbon\Carbon;

class AnnualProfitLossClosingController extends Controller
{
    protected AnnualProfitLossClosingService $annualProfitLossClosingService;

    public function __construct(AnnualProfitLossClosingService $annualProfitLossClosingService)
    {
        $this->annualProfitLossClosingService = $annualProfitLossClosingService;

        $this->middleware('auth');
        // You may need to adjust these permissions to match your seeder/config
        $this->middleware('permission:view-annual-pl-closing@process', ['only' => ['index','status']]);
        $this->middleware('permission:execute-annual-pl-closing@process', ['only' => ['execute']]);
    }

    /**
     * Display the Annual Profit & Loss closing page
     */
    public function index()
    {
        return view('process::annual-profit-loss-closing.index');
    }

    /**
     * Execute Annual Profit & Loss closing
     */
    public function execute(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|date_format:Y',
            'force'  => 'in:true,false,1,0,on,off'
        ]);

        $year = $request->input('year');
        $force = $request->boolean('force', false);

        // Validate that the year is not the current year (should be previous year)
        $currentYear = Carbon::now()->year;
        if ((int) $year >= $currentYear) {
            return response()->json([
                'success' => false,
                'message' => "Annual closing can only be performed for previous years. Current year: {$currentYear}"
            ], 400);
        }

        try {
            if (!$force && $this->annualProfitLossClosingService->isAnnualClosingDone($year)) {
                return response()->json([
                    'success' => false,
                    'message' => "Annual P&L closing for year {$year} has already been posted.",
                    'already_done' => true
                ], 400);
            }

            $result = $this->annualProfitLossClosingService->executeAnnualClosing($year);

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'Annual P&L closing executed.',
                'data' => $result
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during Annual P&L closing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get annual closing status for a year
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|date_format:Y'
        ]);

        $year = $request->input('year');
        $isDone = $this->annualProfitLossClosingService->isAnnualClosingDone($year);

        return response()->json([
            'year' => $year,
            'is_done' => $isDone
        ]);
    }

    /**
     * Get available years for annual closing (last 5 years)
     */
    public function getAvailableYears(): JsonResponse
    {
        $years = $this->annualProfitLossClosingService->getAvailableYears();

        return response()->json($years);
    }
}
