<?php

namespace Modules\FinanceDataMaster\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\FinanceDataMaster\App\Services\FiscalPeriodService;
use Modules\FinanceDataMaster\App\Models\FiscalPeriod;
use App\Models\Setup;
use Carbon\Carbon;

class FiscalPeriodController extends Controller
{
    protected FiscalPeriodService $fiscalPeriodService;

    public function __construct(FiscalPeriodService $fiscalPeriodService)
    {
        $this->fiscalPeriodService = $fiscalPeriodService;

        $this->middleware('auth');
        // You may need to adjust these permissions to match your seeder/config
        $this->middleware('permission:view-fiscal-period@finance', ['only' => ['index', 'status', 'list', 'getAvailablePeriods', 'show']]);
        $this->middleware('permission:manage-fiscal-period@finance', ['only' => ['open', 'close', 'bulkOpen', 'bulkClose', 'updateNotes', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of fiscal periods
     */
    public function index(Request $request)
    {
        $query = FiscalPeriod::query()->orderBy('period', 'desc');

        // Filter by year if provided
        if ($request->has('year') && $request->year) {
            $query->where('period', 'like', $request->year . '-%');
        }

        // Search by period
        if ($request->has('search') && $request->search) {
            $query->where('period', 'like', '%' . $request->search . '%');
        }

        $fiscalPeriods = $query->paginate(20);

        return view('financedatamaster::fiscal-period.index', compact('fiscalPeriods'));
    }

    /**
     * Show the form for creating a new fiscal period
     */
    public function create()
    {
        $startEntryPeriod = \App\Models\Setup::getStartEntryPeriod();
        return view('financedatamaster::fiscal-period.create', compact('startEntryPeriod'));
    }

    /**
     * Store a newly created fiscal period
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m|unique:fiscal_periods,period',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:open,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if the period is before the start entry period
        $startEntryPeriod = Setup::getStartEntryPeriod();
        if ($startEntryPeriod) {
            $periodDate = Carbon::createFromFormat('Y-m', $request->input('period'))->startOfMonth();
            if ($periodDate->lt($startEntryPeriod)) {
                return redirect()->back()
                    ->withErrors(['period' => 'Cannot create fiscal period before the start entry period (' . $startEntryPeriod->format('F d, Y') . ').'])
                    ->withInput();
            }
        }

        $data = $request->only(['period', 'start_date', 'end_date', 'status', 'notes']);

        if ($data['status'] === 'closed') {
            $data['closed_at'] = now();
            $data['closed_by'] = auth()->id();
        }

        FiscalPeriod::create($data);

        return redirect()->route('finance.master-data.fiscal-period.index')
            ->with('success', 'Fiscal period created successfully.');
    }

    /**
     * Store fiscal periods for a whole year
     */
    public function storeYear(Request $request): RedirectResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'status' => 'required|in:open,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $year = $request->input('year');
        $status = $request->input('status');
        $notes = $request->input('notes');
        $created = 0;
        $skipped = 0;
        $excluded = 0;
        $errors = [];

        // Get the start entry period from setup
        $startEntryPeriod = Setup::getStartEntryPeriod();

        try {
            for ($month = 1; $month <= 12; $month++) {
                $period = sprintf('%04d-%02d', $year, $month);
                
                // Calculate start and end dates for the month
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();

                // Skip months before the start entry period
                if ($startEntryPeriod && $startDate->lt($startEntryPeriod)) {
                    $excluded++;
                    continue;
                }
                
                // Check if period already exists
                $existingPeriod = FiscalPeriod::where('period', $period)->first();
                
                if ($existingPeriod) {
                    $skipped++;
                    continue;
                }

                $data = [
                    'period' => $period,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'status' => $status,
                    'notes' => $notes,
                ];

                if ($status === 'closed') {
                    $data['closed_at'] = now();
                    $data['closed_by'] = auth()->id();
                }

                try {
                    FiscalPeriod::create($data);
                    $created++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to create period {$period}: " . $e->getMessage();
                }
            }

            $message = "Successfully created {$created} fiscal period(s) for year {$year}.";
            if ($skipped > 0) {
                $message .= " {$skipped} period(s) already existed and were skipped.";
            }
            if ($excluded > 0) {
                $message .= " {$excluded} period(s) were excluded (before start entry period: " . ($startEntryPeriod ? $startEntryPeriod->format('Y-m-d') : 'N/A') . ").";
            }
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }

            return redirect()->route('finance.master-data.fiscal-period.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('finance.master-data.fiscal-period.create')
                ->with('error', 'Error creating fiscal periods: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified fiscal period
     */
    public function show($id)
    {
        $fiscalPeriod = FiscalPeriod::findOrFail($id);
        return view('financedatamaster::fiscal-period.show', compact('fiscalPeriod'));
    }

    /**
     * Show the form for editing the specified fiscal period
     */
    public function edit($id)
    {
        $fiscalPeriod = FiscalPeriod::findOrFail($id);
        $startEntryPeriod = Setup::getStartEntryPeriod();
        return view('financedatamaster::fiscal-period.edit', compact('fiscalPeriod', 'startEntryPeriod'));
    }

    /**
     * Update the specified fiscal period
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $fiscalPeriod = FiscalPeriod::findOrFail($id);

        $request->validate([
            'period' => 'required|date_format:Y-m|unique:fiscal_periods,period,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:open,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if the period is before the start entry period
        $startEntryPeriod = Setup::getStartEntryPeriod();
        if ($startEntryPeriod) {
            $periodDate = Carbon::createFromFormat('Y-m', $request->input('period'))->startOfMonth();
            if ($periodDate->lt($startEntryPeriod)) {
                return redirect()->back()
                    ->withErrors(['period' => 'Cannot create fiscal period before the start entry period (' . $startEntryPeriod->format('F d, Y') . ').'])
                    ->withInput();
            }
        }

        $data = $request->only(['period', 'start_date', 'end_date', 'status', 'notes']);

        // Handle status change
        if ($data['status'] === 'closed' && $fiscalPeriod->status === 'open') {
            $data['closed_at'] = now();
            $data['closed_by'] = auth()->id();
        } elseif ($data['status'] === 'open' && $fiscalPeriod->status === 'closed') {
            $data['closed_at'] = null;
            $data['closed_by'] = null;
        }

        $fiscalPeriod->update($data);

        return redirect()->route('finance.master-data.fiscal-period.index')
            ->with('success', 'Fiscal period updated successfully.');
    }

    /**
     * Remove the specified fiscal period
     */
    public function destroy($id): RedirectResponse
    {
        if (fiscalPeriod::where('id', $id)->where('status', 'open')->exists()) {
            $fiscalPeriod = FiscalPeriod::findOrFail($id);
            $fiscalPeriod->forceDelete();

            return redirect()->route('finance.master-data.fiscal-period.index')
                ->with('success', 'Fiscal period deleted successfully.');
        } else {
            return redirect()->route('finance.master-data.fiscal-period.index')
                ->with('error', 'cannot delete fiscal period that is closed.');
        }
    }

    /**
     * Display the fiscal period management page (legacy - for open/close operations)
     */
    public function management()
    {
        return view('financedatamaster::fiscal-period.management');
    }

    /**
     * Open a fiscal period
     */
    public function open(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m',
        ]);

        $period = $request->input('period');

        try {
            $result = $this->fiscalPeriodService->openPeriod($period);

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'Period opened.',
                'data' => $result['data'] ?? null
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error opening period: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close a fiscal period
     */
    public function close(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m',
        ]);

        $period = $request->input('period');
        $userId = auth()->id();

        try {
            $result = $this->fiscalPeriodService->closePeriod($period, $userId);

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'Period closed.',
                'data' => $result['data'] ?? null
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error closing period: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get period status
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m'
        ]);

        $period = $request->input('period');
        $result = $this->fiscalPeriodService->getPeriodStatus($period);

        return response()->json($result);
    }

    /**
     * Get all periods
     */
    public function list(Request $request): JsonResponse
    {
        $year = $request->input('year');

        $periods = $this->fiscalPeriodService->getAllPeriods($year ? (int)$year : null);

        return response()->json([
            'success' => true,
            'data' => $periods
        ]);
    }

    /**
     * Bulk open periods
     */
    public function bulkOpen(Request $request): JsonResponse
    {
        $request->validate([
            'periods' => 'required|array',
            'periods.*' => 'required|date_format:Y-m',
        ]);

        try {
            $result = $this->fiscalPeriodService->bulkOpenPeriods($request->input('periods'));

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'Periods opened.',
                'data' => $result
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error opening periods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk close periods
     */
    public function bulkClose(Request $request): JsonResponse
    {
        $request->validate([
            'periods' => 'required|array',
            'periods.*' => 'required|date_format:Y-m',
        ]);

        try {
            $userId = auth()->id();
            $result = $this->fiscalPeriodService->bulkClosePeriods($request->input('periods'), $userId);

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'Periods closed.',
                'data' => $result
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error closing periods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update period notes
     */
    public function updateNotes(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'required|date_format:Y-m',
            'notes' => 'nullable|string',
        ]);

        try {
            $result = $this->fiscalPeriodService->updateNotes(
                $request->input('period'),
                $request->input('notes')
            );

            return response()->json([
                'success' => (bool)($result['success'] ?? false),
                'message' => $result['message'] ?? 'Notes updated.',
                'data' => $result['data'] ?? null
            ], ($result['success'] ?? false) ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating notes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available periods for selection
     * Fetches periods from fiscal_periods table
     */
    public function getAvailablePeriods(): JsonResponse
    {
        // Fetch periods from fiscal_periods table, ordered by period descending
        $fiscalPeriods = FiscalPeriod::orderBy('period', 'desc')
            ->limit(24) // Limit to last 24 periods
            ->get();

        $periods = [];

        if ($fiscalPeriods->isEmpty()) {
            // If no fiscal periods exist, generate last 24 months as fallback
            for ($i = 0; $i < 24; $i++) {
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

