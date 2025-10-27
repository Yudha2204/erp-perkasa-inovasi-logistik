<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CheckTransactionDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $is_ajax = false)
    {
        // Early return if no date fields in request
        $requestData = $request->all();
        $dateFields = $this->getDateFields($requestData);

        if (empty($dateFields)) {
            return $next($request);
        }

        // Get start entry period with caching
        $startEntryPeriod = $this->getCachedStartEntryPeriod();

        if (!$startEntryPeriod) {
        return $next($request);

            return response()->json([
                'errors' => [
                    "Setup" => ["Cant do A Transaction Before Setup"]
                ]
            ], 422);
        }

        // Validate date fields
        foreach ($dateFields as $field => $value) {
            if ($this->isDateBeforeStartPeriod($value, $startEntryPeriod)) {
                if ($is_ajax) {
                    return response()->json([
                        'errors' => [
                            $field => ["Transaction date cannot be before the start entry period ({$startEntryPeriod->format('d/m/Y')})"]
                        ]
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors([
                        $field => "Transaction date cannot be before the start entry period ({$startEntryPeriod->format('d/m/Y')})"
                    ])
                    ->withInput();
            }
        }

        return $next($request);
    }

    /**
     * Get date fields from request data efficiently
     */
    private function getDateFields(array $requestData): array
    {
        $dateFields = [];

        foreach ($requestData as $field => $value) {
            if (str_contains(strtolower($field), 'date') && !empty($value)) {
                $dateFields[$field] = $value;
            }
        }

        return $dateFields;
    }

    /**
     * Get start entry period with caching
     */
    private function getCachedStartEntryPeriod(): ?Carbon
    {
        return Cache::remember('setup_start_entry_period', 3600, function () {
            return Setup::getStartEntryPeriod();
        });
    }

    /**
     * Check if date is before start period (optimized)
     */
    private function isDateBeforeStartPeriod(string $date, Carbon $startPeriod): bool
    {
        try {
            $transactionDate = Carbon::parse($date);
            return $transactionDate->lt($startPeriod);
        } catch (\Exception $e) {
            // If date parsing fails, allow the request to continue
            // The form validation will catch invalid dates
            return false;
        }
    }
}
