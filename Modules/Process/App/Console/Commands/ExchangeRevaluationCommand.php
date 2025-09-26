<?php

namespace Modules\Process\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Process\App\Services\ExchangeRevaluationService;
use Carbon\Carbon;

class ExchangeRevaluationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:exchange-revaluate 
                            {period? : Period to revaluate in Y-m format (e.g., 2024-08). Defaults to previous month} 
                            {--force : Force revaluation even if already done}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute monthly exchange revaluation for all BS accounts with non-IDR currency';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->argument('period');
        $force = $this->option('force');
        
        // If no period provided, use previous month
        if (!$period) {
            $period = Carbon::now()->subMonth()->format('Y-m');
        }
        
        // Validate period format
        try {
            Carbon::createFromFormat('Y-m', $period);
        } catch (\Exception $e) {
            $this->error('Invalid period format. Please use Y-m format (e.g., 2024-08)');
            return 1;
        }
        
        $this->info("Starting exchange revaluation for period: {$period}");
        
        try {
            $service = new ExchangeRevaluationService();
            
            // Check if revaluation already done
            if (!$force && $service->isRevaluationDone($period)) {
                $this->warn("Revaluation for period {$period} has already been done.");
                if (!$this->confirm('Do you want to proceed anyway?')) {
                    $this->info('Revaluation cancelled.');
                    return 0;
                }
            }
            
            // Execute revaluation
            $result = $service->executeMonthlyRevaluation($period);
            
            if ($result['success']) {
                $this->info('✅ Exchange revaluation completed successfully!');
                $this->info("Period: {$result['period']}");
                $this->info("Total accounts processed: {$result['total_accounts_processed']}");
                $this->info("Accounts with revaluation: {$result['accounts_with_revaluation']}");
                $this->info("Total revaluation amount: " . number_format($result['total_revaluation_amount'], 2) . " IDR");
                
                if (!empty($result['details'])) {
                    $this->newLine();
                    $this->info('Revaluation details:');
                    
                    $headers = ['Account Code', 'Account Name', 'Currency', 'Balance FC', 'Exchange Rate', 'Revaluation Amount'];
                    $rows = [];
                    
                    foreach ($result['details'] as $detail) {
                        $rows[] = [
                            $detail['account_code'],
                            $detail['account_name'],
                            $detail['currency'],
                            number_format($detail['balance_fc'] ?? 0, 2),
                            number_format($detail['exchange_rate'] ?? 0, 4),
                            number_format($detail['revaluation_amount'], 2) . ' IDR'
                        ];
                    }
                    
                    $this->table($headers, $rows);
                }
            } else {
                $this->error('❌ Exchange revaluation failed!');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error during revaluation: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
