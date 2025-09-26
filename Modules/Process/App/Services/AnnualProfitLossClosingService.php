<?php

namespace Modules\Process\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;

class AnnualProfitLossClosingService
{
    /**
     * Execute annual Profit & Loss closing by:
     * 1. Resetting accumulated balances from Jan-Dec of previous year for all P&L accounts and Profit Loss Summary
     * 2. Creating automatic reversing journal: Current Earning (Debit) - Retained Earning (Credit)
     *
     * @param string $year Format: Y (e.g., '2024')
     * @return array
     */
    public function executeAnnualClosing(string $year): array
    {
        $yearInt = (int) $year;
        $startDate = Carbon::createFromFormat('Y', $year)->startOfYear();
        $endDate = Carbon::createFromFormat('Y', $year)->endOfYear();
        $nextYear = $yearInt + 1;
        $postingDate = Carbon::createFromFormat('Y', $nextYear)->startOfYear(); // January 1st of next year

        if ($this->isAnnualClosingDone($year)) {
            return [
                'success' => false,
                'message' => "Annual P&L closing for year {$year} has already been posted.",
                'already_done' => true
            ];
        }

        // Calculate accumulated P&L for the entire year (Jan-Dec)
        $accumulatedPL = $this->calculateAccumulatedPL($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

        // If zero, nothing to post
        if (abs($accumulatedPL) < 0.01) {
            return [
                'success' => true,
                'message' => 'No accumulated P&L for the year. No journal posted.',
                'year' => $year,
                'accumulated_pl' => 0,
            ];
        }

        DB::beginTransaction();
        try {
            // Step 1: Reset accumulated balances for all P&L accounts and Profit Loss Summary
            $this->resetAccumulatedBalances($startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $postingDate->format('Y-m-d'));
            
            // Step 2: Create reversing journal: Current Earning (Debit) - Retained Earning (Credit)
            $this->createReversingJournal($accumulatedPL, $postingDate);
            
            DB::commit();

            return [
                'success' => true,
                'year' => $year,
                'accumulated_pl' => $accumulatedPL,
                'message' => 'Annual P&L closing executed successfully. Accumulated balances reset and reversing journal posted.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate accumulated P&L for the entire year by summing all P&L accounts and Profit Loss Summary
     */
    private function calculateAccumulatedPL(string $startDate, string $endDate): float
    {
        // Sum over all P&L accounts and Profit Loss Summary within the year
        $totals = BalanceAccount::whereBetween('date', [$startDate, $endDate])
            ->whereHas('master_account.account_type', function ($q) {
                $q->whereIn('report_type', ['PL'])
                   ->orWhere('id', 23); // Profit Loss Summary account type
            })
            ->selectRaw('COALESCE(SUM(credit),0) as total_credit, COALESCE(SUM(debit),0) as total_debit')
            ->first();

        $totalCredit = (float)($totals->total_credit ?? 0);
        $totalDebit = (float)($totals->total_debit ?? 0);

        // Profit positive, Loss negative
        return $totalCredit - $totalDebit;
    }

    /**
     * Reset accumulated balances for all P&L accounts and Profit Loss Summary
     * by creating offsetting entries on January 1st of the next year
     */
    private function resetAccumulatedBalances(string $startDate, string $endDate, string $postingDate): void
    {
        // Get all P&L accounts and Profit Loss Summary
        $accounts = MasterAccount::whereHas('account_type', function ($q) {
            $q->whereIn('report_type', ['PL'])
               ->orWhere('id', 23); // Profit Loss Summary account type
        })->get();

        // Define a transaction type ID for annual P&L closing (reserved custom id)
        $transactionTypeId = 101; // Annual Profit/Loss Closing

        foreach ($accounts as $account) {
            // Calculate the net balance for this account during the year
            $accountBalance = BalanceAccount::whereBetween('date', [$startDate, $endDate])
                ->where('master_account_id', $account->id)
                ->selectRaw('COALESCE(SUM(credit),0) as total_credit, COALESCE(SUM(debit),0) as total_debit')
                ->first();

            $totalCredit = (float)($accountBalance->total_credit ?? 0);
            $totalDebit = (float)($accountBalance->total_debit ?? 0);
            $netBalance = $totalCredit - $totalDebit;

            // If there's a balance, create offsetting entries to reset it
            if (abs($netBalance) > 0.01) {
                if ($netBalance > 0) {
                    // Credit balance: Debit the account to reset
                    BalanceAccount::create([
                        'master_account_id' => $account->id,
                        'transaction_type_id' => $transactionTypeId,
                        'debit' => $netBalance,
                        'credit' => 0,
                        'date' => $postingDate,
                    ]);
                } else {
                    // Debit balance: Credit the account to reset
                    BalanceAccount::create([
                        'master_account_id' => $account->id,
                        'transaction_type_id' => $transactionTypeId,
                        'debit' => 0,
                        'credit' => abs($netBalance),
                        'date' => $postingDate,
                    ]);
                }
            }
        }
    }

    /**
     * Create reversing journal: Current Earning (Debit) - Retained Earning (Credit)
     * This transfers the accumulated P&L to retained earnings
     */
    private function createReversingJournal(float $accumulatedPL, Carbon $postingDate): void
    {
        // Get Current Earning and Retained Earning accounts
        $currentEarningAccount = MasterAccount::where('account_type_id', 13)->first(); // Current Earning
        $retainedEarningAccount = MasterAccount::where('account_type_id', 14)->first(); // Retained Earning

        if (!$currentEarningAccount || !$retainedEarningAccount) {
            throw new \Exception('MasterAccount for Current Earning or Retained Earning not found');
        }

        // Define a transaction type ID for annual P&L closing (reserved custom id)
        $transactionTypeId = 101; // Annual Profit/Loss Closing

        $amount = abs($accumulatedPL);
        $postingDateStr = $postingDate->format('Y-m-d');

        if ($accumulatedPL > 0) {
            // Profit: Debit Current Earning, Credit Retained Earning
            BalanceAccount::create([
                'master_account_id' => $currentEarningAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'debit' => $amount,
                'credit' => 0,
                'date' => $postingDateStr,
            ]);

            BalanceAccount::create([
                'master_account_id' => $retainedEarningAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'debit' => 0,
                'credit' => $amount,
                'date' => $postingDateStr,
            ]);
        } else {
            // Loss: Debit Retained Earning, Credit Current Earning
            BalanceAccount::create([
                'master_account_id' => $retainedEarningAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'debit' => $amount,
                'credit' => 0,
                'date' => $postingDateStr,
            ]);

            BalanceAccount::create([
                'master_account_id' => $currentEarningAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'debit' => 0,
                'credit' => $amount,
                'date' => $postingDateStr,
            ]);
        }
    }

    /**
     * Check if annual closing has already been posted for the year.
     */
    public function isAnnualClosingDone(string $year): bool
    {
        $nextYear = (int) $year + 1;
        $postingDate = Carbon::createFromFormat('Y', $nextYear)->startOfYear()->format('Y-m-d');

        return BalanceAccount::where('transaction_type_id', 101)
            ->where('date', $postingDate)
            ->exists();
    }

    /**
     * Get available years for annual closing (last 5 years)
     */
    public function getAvailableYears(): array
    {
        $years = [];
        $currentYear = Carbon::now()->year;

        for ($i = 1; $i <= 5; $i++) {
            $year = $currentYear - $i;
            $years[] = [
                'value' => (string) $year,
                'label' => (string) $year
            ];
        }

        return $years;
    }
}
