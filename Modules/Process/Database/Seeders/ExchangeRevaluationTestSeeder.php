<?php

namespace Modules\Process\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExchangeRevaluationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // 1. Create test currencies if not exist
        $usdCurrency = DB::table('master_currency')->where('initial', 'USD')->first();
        if (!$usdCurrency) {
            $usdId = DB::table('master_currency')->insertGetId([
                'initial' => 'USD',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $usdId = $usdCurrency->id;
        }
        
        $sgdCurrency = DB::table('master_currency')->where('initial', 'SGD')->first();
        if (!$sgdCurrency) {
            $sgdId = DB::table('master_currency')->insertGetId([
                'initial' => 'SGD',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $sgdId = $sgdCurrency->id;
        }
        
        // 2. Create test BS accounts with foreign currency
        $testAccounts = [
            [
                'account_type_id' => 1, // Cash
                'code' => '110001-USD',
                'account_name' => 'Kas USD',
                'master_currency_id' => $usdId,
                'can_delete' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'account_type_id' => 2, // Bank
                'code' => '110002-USD',
                'account_name' => 'Bank USD',
                'master_currency_id' => $usdId,
                'can_delete' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'account_type_id' => 1, // Cash
                'code' => '110001-SGD',
                'account_name' => 'Kas SGD',
                'master_currency_id' => $sgdId,
                'can_delete' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        foreach ($testAccounts as $account) {
            DB::table('master_account')->insertOrIgnore($account);
        }
        
        // 3. Create exchange rates for testing
        $testRates = [
            [
                'date' => '2024-08-31',
                'from_currency_id' => $usdId,
                'from_nominal' => 1,
                'to_currency_id' => 1, // IDR
                'to_nominal' => 15000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'date' => '2024-08-31',
                'from_currency_id' => $sgdId,
                'from_nominal' => 1,
                'to_currency_id' => 1, // IDR
                'to_nominal' => 11000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        foreach ($testRates as $rate) {
            DB::table('master_exchange')->insertOrIgnore($rate);
        }
        
        // 4. Create sample transactions
        $usdAccount = DB::table('master_account')->where('code', '110001-USD')->first();
        $sgdAccount = DB::table('master_account')->where('code', '110001-SGD')->first();
        
        if ($usdAccount) {
            // Sample USD transactions
            DB::table('balance_account_data')->insertOrIgnore([
                'master_account_id' => $usdAccount->id,
                'transaction_type_id' => 2, // Regular transaction
                'debit' => 1000, // $1000
                'credit' => 0,
                'date' => '2024-08-15',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        if ($sgdAccount) {
            // Sample SGD transactions
            DB::table('balance_account_data')->insertOrIgnore([
                'master_account_id' => $sgdAccount->id,
                'transaction_type_id' => 2, // Regular transaction
                'debit' => 2000, // SGD 2000
                'credit' => 0,
                'date' => '2024-08-20',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        $this->command->info('Exchange Revaluation test data created successfully!');
        $this->command->info('Test accounts:');
        $this->command->info('- Kas USD: $1000');
        $this->command->info('- Kas SGD: SGD 2000');
        $this->command->info('Exchange rates:');
        $this->command->info('- USD/IDR: 15000');
        $this->command->info('- SGD/IDR: 11000');
    }
}
