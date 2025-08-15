<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
            MasterCurrencySeeder::class,
            MasterTermOfPaymentSeeder::class,
            AccountTypeSeeder::class,
            MasterAccountSeeder::class,
            TransactionTypeSeeder::class,
            MasterTaxSeeder::class,
            MasterContactSeeder::class,
            MarketingExportSeeder::class,
            MarketingImportSeeder::class,
            OperationExportSeeder::class,
            OperationImportSeeder::class
            
        ]);
    }
}
