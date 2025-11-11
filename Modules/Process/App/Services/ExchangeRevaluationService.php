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
        
        // Get IDR currency ID
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }
        
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
            ->whereHas('currency', function($query) use ($idrCurrency) {
                $query->where('id', '!=', $idrCurrency->id);
            })
            ->with(['currency', 'account_type'])
            ->get();
        
        $results = [];
        $totalRevaluationAmount = 0;
        
        DB::beginTransaction();
        
        try {
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
        $balanceData = BalanceAccount::where('master_account_id', $account->id)
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
        $exchangeRate = ExchangeRate::where('from_currency_id', $currency->id)
            ->where('to_currency_id', 1) // IDR currency ID
            ->where('date', $date->format('Y-m-d'))
            ->first();
        
        if (!$exchangeRate) {
            // Try to get the latest available rate before the date
            $exchangeRate = ExchangeRate::where('from_currency_id', $currency->id)
                ->where('to_currency_id', 1)
                ->where('date', '<=', $date->format('Y-m-d'))
                ->orderBy('date', 'desc')
                ->first();
        }
        
        if (!$exchangeRate) {
            return null;
        }
        
        // Calculate rate: IDR/FC = to_nominal / from_nominal
        return $exchangeRate->to_nominal / $exchangeRate->from_nominal;
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
        // Get the current IDR balance from previous revaluations
        // This is calculated by summing all revaluation transactions for this account
        $revaluationBalance = BalanceAccount::where('master_account_id', $account->id)
            ->where('transaction_type_id', 99) // Revaluation transaction type
            ->selectRaw('SUM(debit) - SUM(credit) as net_balance')
            ->first();
        
        return $revaluationBalance->net_balance ?? 0;
    }
    
    /**
     * Create journal entries for revaluation
     * 
     * @param MasterAccount $account
     * @param MasterAccount $exchangePLAccount
     * @param float $revaluationAmount
     * @param Carbon $date
     */
    private function createJournalEntries(MasterAccount $account, MasterAccount $exchangePLAccount, float $revaluationAmount, Carbon $date): void
    {
        // Determine transaction type ID for revaluation (you may need to create this)
        $transactionTypeId = 99; // Assuming 99 is for revaluation transactions
        
        // Get IDR currency ID (revaluation amounts are in IDR)
        $idrCurrency = MasterCurrency::where('initial', 'IDR')->first();
        if (!$idrCurrency) {
            throw new \Exception('IDR currency not found');
        }
        
        if ($revaluationAmount > 0) {
            // Positive revaluation - debit the account, credit Exchange P/L
            if ($account->account_type->normal_side === 'debit') {
                // Asset account - debit to increase
                BalanceAccount::create([
                    'master_account_id' => $account->id,
                    'transaction_type_id' => $transactionTypeId,
                    'currency_id' => $idrCurrency->id,
                    'debit' => $revaluationAmount,
                    'credit' => 0,
                    'date' => $date->format('Y-m-d'),
                ]);
            } else {
                // Liability account - credit to increase
                BalanceAccount::create([
                    'master_account_id' => $account->id,
                    'transaction_type_id' => $transactionTypeId,
                    'currency_id' => $idrCurrency->id,
                    'debit' => 0,
                    'credit' => $revaluationAmount,
                    'date' => $date->format('Y-m-d'),
                ]);
            }
            
            // Credit Exchange P/L
            BalanceAccount::create([
                'master_account_id' => $exchangePLAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'currency_id' => $idrCurrency->id,
                'debit' => 0,
                'credit' => $revaluationAmount,
                'date' => $date->format('Y-m-d'),
            ]);
            
        } else {
            // Negative revaluation - credit the account, debit Exchange P/L
            $absAmount = abs($revaluationAmount);
            
            if ($account->account_type->normal_side === 'debit') {
                // Asset account - credit to decrease
                BalanceAccount::create([
                    'master_account_id' => $account->id,
                    'transaction_type_id' => $transactionTypeId,
                    'currency_id' => $idrCurrency->id,
                    'debit' => 0,
                    'credit' => $absAmount,
                    'date' => $date->format('Y-m-d'),
                ]);
            } else {
                // Liability account - debit to decrease
                BalanceAccount::create([
                    'master_account_id' => $account->id,
                    'transaction_type_id' => $transactionTypeId,
                    'currency_id' => $idrCurrency->id,
                    'debit' => $absAmount,
                    'credit' => 0,
                    'date' => $date->format('Y-m-d'),
                ]);
            }
            
            // Debit Exchange P/L
            BalanceAccount::create([
                'master_account_id' => $exchangePLAccount->id,
                'transaction_type_id' => $transactionTypeId,
                'currency_id' => $idrCurrency->id,
                'debit' => $absAmount,
                'credit' => 0,
                'date' => $date->format('Y-m-d'),
            ]);
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
