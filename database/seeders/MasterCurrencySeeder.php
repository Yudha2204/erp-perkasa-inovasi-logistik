<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_simple = [
            ["IDR", "Rupiah", "0"],
            ["SGD", "Singapore Dollar", "0"],
            ["USD", "US Dollar", "0"],
            ["EUR", "Euro", "0"],
        ];

        for ($i=0; $i < count($array_simple); $i++) { 
            DB::table('master_currency')->insert([
                'initial' => $array_simple[$i][0],
                'currency_name' => $array_simple[$i][1],
                'can_delete' => $array_simple[$i][2],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
