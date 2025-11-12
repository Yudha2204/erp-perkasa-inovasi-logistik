<?php

namespace Modules\Process\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\FinanceDataMaster\App\Models\MasterAccount;
use Modules\FinanceDataMaster\App\Models\AccountType;
use Modules\FinanceDataMaster\App\Models\MasterCurrency;
use Modules\FinanceDataMaster\App\Models\BalanceAccount;
use Modules\ExchangeRate\App\Models\ExchangeRate;

class ExchangeRevaluationService
{
    private $idrCurrencyId;

    private function getIdrCurrencyId()
    {
        if (!$this->idrCurrencyId) {
            $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
            if (!$idrCurrency) {
                throw new \Exception('IDR currency not found');
            }
            $this->idrCurrencyId = $idrCurrency->id;
        }
        return $this->idrCurrencyId;
    }
    /**
     * Execute monthly exchange revaluation for all BS accounts with non-IDR currency
     * 
     * @param string $period Format: Y-m (e.g., '2024-08')
     * @return array
     */
    public function executeMonthlyRevaluation(string $period): array
    {
        $startDate = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $period)->endOfMonth();
        $idrCurrencyId = $this->getIdrCurrencyId();

        
        // Get Exchange Profit/Loss account
        $exchangePLAccount = MasterAccount::where('account_type_id', '22')
        ->first();
        
        if (!$exchangePLAccount) {
            throw new \Exception('Exchange Profit/Loss account not found');
        }
        
        // Get all BS accounts with non-IDR currency
        $bsAccounts = MasterAccount::whereHas('account_type', function($query) {
                $query->where('report_type', 'BS');
            })
            ->whereHas('currency', function($query) use ($idrCurrencyId) {
                $query->where('id', '!=', $idrCurrencyId);
            })
            ->with(['currency', 'account_type'])
            ->get();
            
            $results = [];
            $totalRevaluationAmount = 0;
            
            DB::beginTransaction();
            
            try {
                BalanceAccount::where('transaction_type_id', 99) // Asumsi 99 adalah Tipe Transaksi Revaluasi
                ->where('date', $endDate->format('Y-m-d'))
                ->delete();

            foreach ($bsAccounts as $account) {
                $revaluationResult = $this->revaluateAccount($account, $endDate, $exchangePLAccount);
                
                if ($revaluationResult['has_revaluation']) {
                    $results[] = $revaluationResult;
                    $totalRevaluationAmount += $revaluationResult['revaluation_amount'];
                }
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'period' => $period,
                'total_accounts_processed' => $bsAccounts->count(),
                'accounts_with_revaluation' => count($results),
                'total_revaluation_amount' => $totalRevaluationAmount,
                'details' => $results
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Revaluate a specific account
     * 
     * @param MasterAccount $account
     * @param Carbon $endDate
     * @param MasterAccount $exchangePLAccount
     * @return array
     */
    private function revaluateAccount(MasterAccount $account, Carbon $endDate, MasterAccount $exchangePLAccount): array
    {
        
        // Get account balance in foreign currency
        $balance = $this->getAccountBalance($account, $endDate);
        
        if ($balance == 0) {
            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->account_name,
                'currency' => $account->currency->initial,
                'has_revaluation' => false,
                'revaluation_amount' => 0
            ];
        }
        // Get exchange rate for end of month
        $exchangeRate = $this->getExchangeRate($account->currency, $endDate);
        
        if (!$exchangeRate) {
            throw new \Exception("Exchange rate not found for {$account->currency->initial} on {$endDate->format('Y-m-d')}");
        }
        
        // Calculate revaluation amount
        $revaluationAmount = $this->calculateRevaluationAmount($account, $balance, $exchangeRate);
        
        if (abs($revaluationAmount) < 0.01) { // Ignore very small amounts
            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->account_name,
                'currency' => $account->currency->initial,
                'has_revaluation' => false,
                'revaluation_amount' => 0
            ];
        }
        
        // Create journal entries
        $this->createJournalEntries($account, $exchangePLAccount, $revaluationAmount, $endDate);
        
        return [
            'account_id' => $account->id,
            'account_code' => $account->code,
            'account_name' => $account->account_name,
            'currency' => $account->currency->initial,
            'balance_fc' => $balance,
            'exchange_rate' => $exchangeRate,
            'has_revaluation' => true,
            'revaluation_amount' => $revaluationAmount
        ];
    }
    
    /**
     * Get account balance in foreign currency
     * 
     * @param MasterAccount $account
     * @param Carbon $endDate
     * @return float
     */
    private function getAccountBalance(MasterAccount $account, Carbon $endDate): float
    {
        $balanceData = BalanceAccount::withoutGlobalScope('debit_priority')
            ->where('master_account_id', $account->id)
            ->where('currency_id', $account->master_currency_id)
            ->where('transaction_type_id', '!=', 99) // Exclude revaluation transactions
            ->where('date', '<=', $endDate->format('Y-m-d'))
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();
        $totalDebit = $balanceData->total_debit ?? 0;
        $totalCredit = $balanceData->total_credit ?? 0;
        
        // Calculate net balance based on account type normal side
        if ($account->account_type->normal_side === 'debit') {
            return $totalDebit - $totalCredit;
        } else {
            return $totalCredit - $totalDebit;
        }
    }
    
    /**
     * Get exchange rate for specific currency and date
     * 
     * @param MasterCurrency $currency
     * @param Carbon $date
     * @return float|null
     */
    private function getExchangeRate(MasterCurrency $currency, Carbon $date): ?float
    {
        $idrCurrencyId = $this->getIdrCurrencyId();
        $currencyId = $currency->id;
        $rate = ExchangeRate::query()
            ->whereDate('date', $date)
            ->where(function ($q) use ($currencyId, $idrCurrencyId) {
                $q->where(function ($q) use ($currencyId, $idrCurrencyId) {
                    $q->where('from_currency_id', $currencyId)
                        ->where('to_currency_id', $idrCurrencyId);
                })->orWhere(function ($q) use ($currencyId, $idrCurrencyId) {
                    $q->where('from_currency_id', $idrCurrencyId)
                        ->where('to_currency_id', $currencyId);
                });
            })
            // If duplicates can exist for a date, prefer the latest updated/inserted:
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if (!$rate) {
            throw new \Exception('Failed to convert amount: exchange rate not found for the given date/currencies.');
        }

        if (empty($rate->from_nominal) || empty($rate->to_nominal)) {
            throw new \Exception('Invalid exchange rate record: nominal values cannot be zero or null.');
        }

        $isDirect = ((int)$rate->from_currency_id === $currencyId)
            && ((int)$rate->to_currency_id === $idrCurrencyId);

        $factor = $isDirect
            ? ($rate->to_nominal / $rate->from_nominal)
            : ($rate->from_nominal / $rate->to_nominal);

        return $factor;
    }
    
    /**
     * Calculate revaluation amount
     * 
     * @param MasterAccount $account
     * @param float $balance
     * @param float $exchangeRate
     * @return float
     */
    private function calculateRevaluationAmount(MasterAccount $account, float $balance, float $exchangeRate): float
    {
        // Convert foreign currency balance to IDR
        $balanceInIDR = $balance * $exchangeRate;
        
        // Get current IDR balance from previous revaluations
        $currentIDRBalance = $this->getCurrentIDRBalance($account);
        
        // Calculate revaluation amount
        $revaluationAmount = $balanceInIDR - $currentIDRBalance;
        
        return $revaluationAmount;
    }
    
    /**
     * Get current IDR balance from previous revaluations
     * 
     * @param MasterAccount $account
     * @return float
     */
    private function getCurrentIDRBalance(MasterAccount $account): float
    {
        $idrCurrencyId = $this->getIdrCurrencyId();

        // Get the current IDR balance from previous revaluations
        // This is calculated by summing all revaluation transactions for this account
        $balanceData = BalanceAccount::withoutGlobalScope('debit_priority')
            ->where('master_account_id', $account->id)
            ->where('currency_id', $idrCurrencyId) 
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();
        
        $totalDebit = $balanceData->total_debit ?? 0;
        $totalCredit = $balanceData->total_credit ?? 0;

        // Saldo IDR harus mengikuti normal side dari akun valas-nya
        if ($account->account_type->normal_side === 'debit') {
            // Aset: Saldo normal Debit
            return $totalDebit - $totalCredit;
        } else {
            // Kewajiban: Saldo normal Kredit
            return $totalCredit - $totalDebit;
        }
    }
    
    /**
     * Helper function to create a single balance entry
     */
    private function createBalanceEntry(int $accountId, int $trxTypeId, int $currencyId, float $debit, float $credit, Carbon $date): void
    {
        BalanceAccount::create([
            'master_account_id' => $accountId,
            'transaction_type_id' => $trxTypeId,
            'currency_id' => $currencyId,
            'debit' => $debit,
            'credit' => $credit,
            'date' => $date->format('Y-m-d'),
        ]);
    }

    /**
     * Create journal entries for revaluation (Corrected Logic)
     * * @param MasterAccount $account
     * @param MasterAccount $exchangePLAccount
     * @param float $revaluationAmount
     * @param Carbon $date
     */
    private function createJournalEntries(MasterAccount $account, MasterAccount $exchangePLAccount, float $revaluationAmount, Carbon $date): void
    {
        $transactionTypeId = 99; // Revaluation transaction type
        $idrCurrencyId = $this->getIdrCurrencyId();
        
        if ($revaluationAmount > 0) {
            // Revaluation amount is positive
            if ($account->account_type->normal_side === 'debit') {
                // Case 1: Asset Increased (e.g., Bank USD value went up)
                // (Dr) Asset Account
                $this->createBalanceEntry($account->id, $transactionTypeId, $idrCurrencyId, $revaluationAmount, 0, $date);
                // (Cr) P/L Account (Unrealized Gain)
                $this->createBalanceEntry($exchangePLAccount->id, $transactionTypeId, $idrCurrencyId, 0, $revaluationAmount, $date);
            } else {
                // Case 2: Liability Increased (e.g., AP USD value went up)
                // (Cr) Liability Account
                $this->createBalanceEntry($account->id, $transactionTypeId, $idrCurrencyId, 0, $revaluationAmount, $date);
                // (Dr) P/L Account (Unrealized Loss)
                $this->createBalanceEntry($exchangePLAccount->id, $transactionTypeId, $idrCurrencyId, $revaluationAmount, 0, $date);
            }
        } else {
            // Revaluation amount is negative or zero
            $absAmount = abs($revaluationAmount);
            
            if ($absAmount < 0.01) {
                return; // Do nothing if amount is effectively zero
            }

            if ($account->account_type->normal_side === 'debit') {
                // Case 3: Asset Decreased (e.g., Bank USD value went down)
                // (Cr) Asset Account
                $this->createBalanceEntry($account->id, $transactionTypeId, $idrCurrencyId, 0, $absAmount, $date);
                // (Dr) P/L Account (Unrealized Loss)
                $this->createBalanceEntry($exchangePLAccount->id, $transactionTypeId, $idrCurrencyId, $absAmount, 0, $date);
            } else {
                // Case 4: Liability Decreased (e.g., AP USD value went down)
                // (Dr) Liability Account
                $this->createBalanceEntry($account->id, $transactionTypeId, $idrCurrencyId, $absAmount, 0, $date);
                // (Cr) P/L Account (Unrealized Gain)
                $this->createBalanceEntry($exchangePLAccount->id, $transactionTypeId, $idrCurrencyId, 0, $absAmount, $date);
            }
        }
    }
    
    /**
     * Check if revaluation has been done for a specific period
     * 
     * @param string $period
     * @return bool
     */
    public function isRevaluationDone(string $period): bool
    {
        $endDate = Carbon::createFromFormat('Y-m', $period)->endOfMonth();
        
        // Check if there are any revaluation transactions for this period
        $hasRevaluation = BalanceAccount::whereHas('master_account', function($query) {
                $query->where('account_type_id', 22);
            })
            ->where('date', $endDate->format('Y-m-d'))
            ->where('transaction_type_id', 99) // Assuming 99 is revaluation transaction type
            ->exists();
        
        return $hasRevaluation;
    }
}
