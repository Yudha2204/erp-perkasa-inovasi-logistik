<?php

namespace Modules\Process\Database\Seeders;

use Illuminate\Database\Seeder;

class ProcessDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ExchangeRevaluationTestSeeder::class,
        ]);
    }
}
