<?php

namespace Modules\Process\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;

class ProfitLossClosingService
{
    /**
     * Execute monthly Profit & Loss closing by posting journal:
     *   Profit Loss Summary (Debit) vs Current Earning (Credit)
     * If the month ends in a loss, the entry is reversed.
     *
     * @param string $period Format: Y-m (e.g., '2025-08')
     * @return array
     */
    public function executeMonthlyClosing(string $period): array
    {
        $startDate = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $period)->endOfMonth();

        if ($this->isClosingDone($period)) {
            return [
                'success' => false,
                'message' => "P&L closing for period {$period} has already been posted.",
                'already_done' => true
            ];
        }

        // Calculate net P&L for the month (credit - debit for all PL accounts)
        $netPL = $this->calculateNetPL($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

        // If zero, nothing to post
        if (abs($netPL) < 0.01) {
            return [
                'success' => true,
                'message' => 'No P&L movement for the period. No journal posted.',
                'period' => $period,
                'net_pl' => 0,
            ];
        }

        DB::beginTransaction();
        try {
            $this->createJournalEntries($netPL, $endDate);
            DB::commit();

            return [
                'success' => true,
                'period' => $period,
                'net_pl' => $netPL,
                'message' => 'P&L closing journal posted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Compute net P&L for given date range by summing all PL accounts
     * using BalanceAccount entries.
     */
    private function calculateNetPL(string $startDate, string $endDate): float
    {
        // Sum over all PL accounts within the month
        $totals = BalanceAccount::whereBetween('date', [$startDate, $endDate])
            ->whereHas('master_account.account_type', function ($q) {
                $q->where('report_type', 'PL');
            })
            ->selectRaw('COALESCE(SUM(credit),0) as total_credit, COALESCE(SUM(debit),0) as total_debit')
            ->first();

        $totalCredit = (float)($totals->total_credit ?? 0);
        $totalDebit = (float)($totals->total_debit ?? 0);

        // Profit positive, Loss negative
        return $totalCredit - $totalDebit;
    }

    /**
     * Create closing journal entries between Profit Loss Summary and Current Earning.
     *
     * @param float $netPL Positive = profit, Negative = loss
     * @param Carbon $date Posting date (end of month)
     */
    private function createJournalEntries(float $netPL, Carbon $date): void
    {
        // Use first available MasterAccount under those types
        $plsAccount = MasterAccount::where('account_type_id', 23)->first();
        $ceAccount = MasterAccount::where('account_type_id', 13)->first();

        if (!$plsAccount || !$ceAccount) {
            throw new \Exception('MasterAccount for Profit Loss Summary or Current Earning not found');
        }

        // Get IDR currency ID (P&L closing amounts are in IDR)
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }

        // Define a transaction type ID for P&L closing (reserved custom id)
        $transactionTypeId = 100; // Profit/Loss Closing

        $amount = abs($netPL);
        $postingDate = $date->format('Y-m-d');

        if ($netPL > 0) {
            // Profit: Debit P&L Summary, Credit Current Earning
            BalanceAccount::create([
                'master_account_id' => $plsAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'currency_id' => $idrCurrency->id,
                'debit' => $amount,
                'credit' => 0,
                'date' => $postingDate,
            ]);

            BalanceAccount::create([
                'master_account_id' => $ceAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'currency_id' => $idrCurrency->id,
                'debit' => 0,
                'credit' => $amount,
                'date' => $postingDate,
            ]);
        } else {
            // Loss: Debit Current Earning, Credit P&L Summary
            BalanceAccount::create([
                'master_account_id' => $ceAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'currency_id' => $idrCurrency->id,
                'debit' => $amount,
                'credit' => 0,
                'date' => $postingDate,
            ]);

            BalanceAccount::create([
                'master_account_id' => $plsAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'currency_id' => $idrCurrency->id,
                'debit' => 0,
                'credit' => $amount,
                'date' => $postingDate,
            ]);
        }
    }

    /**
     * Check if closing has already been posted for the period.
     */
    public function isClosingDone(string $period): bool
    {
        $endDate = Carbon::createFromFormat('Y-m', $period)->endOfMonth()->format('Y-m-d');

        return BalanceAccount::where('transaction_type_id', 100)
            ->where('date', $endDate)
            ->exists();
    }
}


