<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperationExportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_simple = [
            ["2", "JOPILBTH-00002", "Jl. Apel", "Riau", "Papua", "Jl. Srikaya", "2", "LCL", "Express JS"],
        ];

        //insert to operation_export table
        for ($i=0; $i < 1; $i++) { 
            DB::table('operation_export')->insert([
                'marketing_export_id' => $array_simple[$i][0],
                'job_order_id' => $array_simple[$i][1],
                'delivery_address' => $array_simple[$i][2],
                'origin' => $array_simple[$i][3],
                'destination' => $array_simple[$i][4],
                'pickup_address' => $array_simple[$i][5],
                'transportation' => $array_simple[$i][6],
                'transportation_desc' => $array_simple[$i][7],
                'recepient_name' => $array_simple[$i][8],
                'status' => "1",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
