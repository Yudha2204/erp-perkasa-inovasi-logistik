<?php

namespace Modules\Process\App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Modules\Process\App\Services\ProfitLossClosingService;

class ProfitLossClosingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:profitloss-close 
                            {period? : Period to close in Y-m format (e.g., 2025-08). Defaults to previous month} 
                            {--force : Force closing even if already done}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post monthly Profit & Loss closing journal between Profit Loss Summary and Current Earning';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->argument('period');
        $force = $this->option('force');

        if (!$period) {
            $period = Carbon::now()->subMonth()->format('Y-m');
        }

        try {
            Carbon::createFromFormat('Y-m', $period);
        } catch (\Exception $e) {
            $this->error('Invalid period format. Please use Y-m (e.g., 2025-08)');
            return 1;
        }

        $this->info("Starting P&L closing for period: {$period}");

        try {
            $service = new ProfitLossClosingService();

            if (!$force && $service->isClosingDone($period)) {
                $this->warn("P&L closing for {$period} has already been posted.");
                return 0;
            }

            $result = $service->executeMonthlyClosing($period);

            if (!($result['success'] ?? false)) {
                $this->warn($result['message'] ?? 'No action performed.');
                return 0;
            }

            $net = number_format((float)($result['net_pl'] ?? 0), 2);
            $this->info("✅ P&L closing posted. Net P&L: {$net}");
        } catch (\Exception $e) {
            $this->error('❌ Error during P&L closing: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}


