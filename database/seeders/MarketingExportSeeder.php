<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketingExportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_simple = [
            ["1", "JOPILBTH-00001", "1", "2", "FCL", "0001", "Export gula tebu", "9999", "200", "ini volumenya", "M3", "Batam", "Ex Shanghai", "Jl. Tambunan", "Jakarta", "Express", "Jl. Mataram", "-", "1"],
            ["2", "JOPILBTH-00002", "1", "2", "LCL", "0002", "Export Minyak Sawit", "9990", "300", "ini volumenya", "M3", "Riau", "Ex Shanghai", "Jl. Srikaya", "Papua", "Express JS", "Jl. Apel", "-", "2"],
        ];

        //insert to marketing_export table
        for ($i=0; $i < 2; $i++) { 
            DB::table('marketing_export')->insert([
                'contact_id' => $array_simple[$i][0],
                'job_order_id' => $array_simple[$i][1],
                'expedition' => $array_simple[$i][2],
                'transportation' => $array_simple[$i][3],
                'transportation_desc' => $array_simple[$i][4],
                'no_po' => $array_simple[$i][5],
                'description' => $array_simple[$i][6],
                'no_cipl' => $array_simple[$i][7],
                'total_weight' => $array_simple[$i][8],
                'total_volume' => "12768",
                'freetext_volume' => $array_simple[$i][10],
                'origin' => $array_simple[$i][11],
                'shipper' => $array_simple[$i][12],
                'pickup_address' => $array_simple[$i][13],
                'destination' => $array_simple[$i][14],
                'consignee' => $array_simple[$i][15],
                'delivery_address' => $array_simple[$i][16],
                'remark' => $array_simple[$i][17],
                'status' => $array_simple[$i][18],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        //insert to dimension_marketing_export table
        $array_dimension = [
            ["1", "1", "12", "13", "14", "2", "10"],
            ["1", "2", "7", "8", "9", "3", "11"],
            ["2", "1", "12", "13", "14", "2", "10"],
            ["2", "2", "7", "8", "9", "3", "11"],
        ];

        for ($i=0; $i < 4; $i++) { 
            DB::table('dimension_marketing_export')->insert([
                'marketing_export_id' => $array_dimension[$i][0],
                'packages' => $array_dimension[$i][1],
                'length' => $array_dimension[$i][2],
                'width' => $array_dimension[$i][3],
                'height' => $array_dimension[$i][4],
                'input_measure' => $array_dimension[$i][5],
                'qty' => $array_dimension[$i][6],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        //insert to quotation_marketing_export table
        $array_quotation = [
            ["1", "99898", "-", "1"],
            ["2", "90000", "-", "1"],
        ];

        for ($i=0; $i < 2; $i++) { 
            DB::table('quotation_marketing_export')->insert([
                'marketing_export_id' => $array_quotation[$i][0],
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'quotation_no' => $array_quotation[$i][1],
                'valid_until' => Carbon::now()->format('Y-m-d H:i:s'),
                'project_desc' => $array_quotation[$i][2],
                'currency_id' => $array_quotation[$i][3],
                'sales_value' => "3000000",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        //insert to group_quotation_marketing_export table
        $array_group = [
            ["1", "A"],
            ["2", "A"],
        ];

        for ($i=0; $i < 2; $i++) { 
            DB::table('group_quotation_m_ex')->insert([
                'quotation_m_ex_id' => $array_group[$i][0],
                'group' => $array_group[$i][1],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        //insert to item_group_quotation_marketing_export table
        $array_group = [
            ["1", "Barang 1", "1000000", "-"],
            ["1", "Barang 2", "2000000", "-"],
            ["2", "Barang 1", "1000000", "-"],
            ["2", "Barang 2", "2000000", "-"],
        ];

        for ($i=0; $i < 4; $i++) { 
            DB::table('item_group_quotation_m_ex')->insert([
                'group_quotation_m_ex_id' => $array_group[$i][0],
                'description' => $array_group[$i][1],
                'total' => $array_group[$i][2],
                'remark' => $array_group[$i][3],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

    }
}
