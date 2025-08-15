<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array_simple = [
            ["AH-001", "Ahmad Habibi", "Manager", "081232123212", "ahmad@gmail.com", "-", ["2"], "Jl. Ketapang", "Batam", "98221", "Indonesia", "08129322123"],
            ["H-001", "Herman", "Manager", "083923912322", "herman@gmail.com", "-", ["1"], "Jl. Wonosari", "Pekanbaru", "09332", "Indonesia", "093829312345"],
            ["H-002", "Hasibuan", "Customer Service", "083927483931", "hasibuan@gmail.com", "-", ["1"], "Jl. Wonokdadi", "Pekanbaru", "09332", "Indonesia", '093829302912'],
        ];

        //insert to master_contact table

        for ($i=0; $i < 3; $i++) { 
            DB::table('master_contact')->insert([
                'customer_id' => $array_simple[$i][0],
                'customer_name' => $array_simple[$i][1],
                'title' => $array_simple[$i][2],
                'phone_number' => $array_simple[$i][3],
                'email' => $array_simple[$i][4],
                'npwp_ktp' => $array_simple[$i][5],
                'type' => json_encode($array_simple[$i][6]),
                'address' => $array_simple[$i][7],
                'city' => $array_simple[$i][8],
                'postal_code' => $array_simple[$i][9],
                'country' => $array_simple[$i][10],
                'mobile_number' => $array_simple[$i][11],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }


        //insert to term_payment_master_contact table

        $array_term = [
            ["1", "1"],
            ["1", "2"],
            ["2", "3"],
        ];

        for ($i=0; $i < 3; $i++) { 
            DB::table('term_payment_master_contact')->insert([
                'term_payment_id' => $array_term[$i][0],
                'contact_id' => $array_term[$i][1],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
