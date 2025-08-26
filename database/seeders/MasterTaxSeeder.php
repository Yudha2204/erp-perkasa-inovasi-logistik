<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_simple = [
            ["Non-Pajak", "Non-Pajak", "0", "1", "PPH", null],
            ["PPh 23-4", "PPh Pasal 23 Non NPWP", "10", "1", "PPH", null],
            ["PPh 24-5", "PPh Pasal 23 NPWP", "5", "1", "PPH", null]
        ];

        for ($i=0; $i < 3; $i++) { 
            DB::table('master_tax')->insert([
                'code' => $array_simple[$i][0],
                'name' => $array_simple[$i][1],
                'tax_rate' => $array_simple[$i][2],
                'status' => $array_simple[$i][3],
                'type' => $array_simple[$i][4],
                'account_id' => $array_simple[$i][5],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
