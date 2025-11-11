<?php

namespace Modules\Process\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Process\App\Models\FiscalPeriod;

class FiscalPeriodService
{
    /**
     * Open a fiscal period
     *
     * @param string $period Format: Y-m (e.g., '2025-01')
     * @return array
     */
    public function openPeriod(string $period): array
    {
        $fiscalPeriod = FiscalPeriod::where('period', $period)->first();

        if (!$fiscalPeriod) {
            // Create period if it doesn't exist
            $date = Carbon::createFromFormat('Y-m', $period);
            $fiscalPeriod = FiscalPeriod::create([
                'period' => $period,
                'start_date' => $date->copy()->startOfMonth(),
                'end_date' => $date->copy()->endOfMonth(),
                'status' => 'open',
            ]);
        } else {
            if ($fiscalPeriod->isOpen()) {
                return [
                    'success' => false,
                    'message' => "Period {$period} is already open.",
                    'already_open' => true
                ];
            }

            $fiscalPeriod->open();
        }

        return [
            'success' => true,
            'message' => "Period {$period} has been opened successfully.",
            'data' => $fiscalPeriod
        ];
    }

    /**
     * Close a fiscal period
     *
     * @param string $period Format: Y-m (e.g., '2025-01')
     * @param int|null $userId User ID who closes the period
     * @return array
     */
    public function closePeriod(string $period, ?int $userId = null): array
    {
        $fiscalPeriod = FiscalPeriod::where('period', $period)->first();

        if (!$fiscalPeriod) {
            // Create period if it doesn't exist
            $date = Carbon::createFromFormat('Y-m', $period);
            $fiscalPeriod = FiscalPeriod::create([
                'period' => $period,
                'start_date' => $date->copy()->startOfMonth(),
                'end_date' => $date->copy()->endOfMonth(),
                'status' => 'closed',
            ]);
            $fiscalPeriod->close($userId);
        } else {
            if ($fiscalPeriod->isClosed()) {
                return [
                    'success' => false,
                    'message' => "Period {$period} is already closed.",
                    'already_closed' => true
                ];
            }

            $fiscalPeriod->close($userId);
        }

        return [
            'success' => true,
            'message' => "Period {$period} has been closed successfully.",
            'data' => $fiscalPeriod
        ];
    }

    /**
     * Get period status
     *
     * @param string $period Format: Y-m (e.g., '2025-01')
     * @return array
     */
    public function getPeriodStatus(string $period): array
    {
        $fiscalPeriod = FiscalPeriod::where('period', $period)->first();

        if (!$fiscalPeriod) {
            // If period doesn't exist, it's considered open by default
            return [
                'period' => $period,
                'status' => 'open',
                'exists' => false
            ];
        }

        return [
            'period' => $period,
            'status' => $fiscalPeriod->status,
            'exists' => true,
            'start_date' => $fiscalPeriod->start_date->format('Y-m-d'),
            'end_date' => $fiscalPeriod->end_date->format('Y-m-d'),
            'closed_at' => $fiscalPeriod->closed_at ? $fiscalPeriod->closed_at->format('Y-m-d H:i:s') : null,
            'closed_by' => $fiscalPeriod->closed_by,
            'notes' => $fiscalPeriod->notes,
        ];
    }

    /**
     * Get all periods with their status
     *
     * @param int $year Optional year filter
     * @return array
     */
    public function getAllPeriods(?int $year = null): array
    {
        $query = FiscalPeriod::query();

        if ($year) {
            $query->where('period', 'like', "{$year}-%");
        }

        $periods = $query->orderBy('period', 'desc')->get();

        return $periods->map(function ($period) {
            return [
                'id' => $period->id,
                'period' => $period->period,
                'start_date' => $period->start_date->format('Y-m-d'),
                'end_date' => $period->end_date->format('Y-m-d'),
                'status' => $period->status,
                'closed_at' => $period->closed_at ? $period->closed_at->format('Y-m-d H:i:s') : null,
                'closed_by' => $period->closed_by,
                'notes' => $period->notes,
            ];
        })->toArray();
    }

    /**
     * Bulk close periods (close multiple periods at once)
     *
     * @param array $periods Array of period strings (e.g., ['2025-01', '2025-02'])
     * @param int|null $userId User ID who closes the periods
     * @return array
     */
    public function bulkClosePeriods(array $periods, ?int $userId = null): array
    {
        $results = [];
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($periods as $period) {
                $result = $this->closePeriod($period, $userId);
                if ($result['success']) {
                    $results[] = $period;
                } else {
                    $errors[] = $result['message'];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => count($results) . ' period(s) closed successfully.',
                'closed_periods' => $results,
                'errors' => $errors
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Bulk open periods
     *
     * @param array $periods Array of period strings
     * @return array
     */
    public function bulkOpenPeriods(array $periods): array
    {
        $results = [];
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($periods as $period) {
                $result = $this->openPeriod($period);
                if ($result['success']) {
                    $results[] = $period;
                } else {
                    $errors[] = $result['message'];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => count($results) . ' period(s) opened successfully.',
                'opened_periods' => $results,
                'errors' => $errors
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update period notes
     *
     * @param string $period
     * @param string|null $notes
     * @return array
     */
    public function updateNotes(string $period, ?string $notes): array
    {
        $fiscalPeriod = FiscalPeriod::where('period', $period)->first();

        if (!$fiscalPeriod) {
            return [
                'success' => false,
                'message' => "Period {$period} not found."
            ];
        }

        $fiscalPeriod->update(['notes' => $notes]);

        return [
            'success' => true,
            'message' => "Notes updated for period {$period}.",
            'data' => $fiscalPeriod
        ];
    }
}

