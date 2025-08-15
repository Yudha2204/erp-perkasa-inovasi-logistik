<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterTermOfPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_simple = [
            ["Cash On Delivery", "COD", "", "0"],
            ["14 Days", "14Days", "", "14"],
        ];

        for ($i=0; $i < 2; $i++) { 
            DB::table('master_term_of_payment')->insert([
                'name' => $array_simple[$i][0],
                'code' => $array_simple[$i][1],
                'description' => $array_simple[$i][2],
                'pay_days' => $array_simple[$i][3],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
