<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $rows = [
            // --- Assets (BS) ---
            ['code'=>'1-0001','classification'=>'Cash','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'1-0002','classification'=>'Bank','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'1-0003','classification'=>'Current Asset','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'1-0004','classification'=>'Account Receivable','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'1-0005','classification'=>'Fixed Asset','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'1-0006','classification'=>'Other Asset','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'1-0007','classification'=>'Accumulated Depreciation','normal_side'=>'credit','report_type'=>'BS'],
            
            // --- Liabilities (BS) ---
            ['code'=>'2-0001','classification'=>'Account Payable','normal_side'=>'credit','report_type'=>'BS'],
            ['code'=>'2-0002','classification'=>'Other Payable','normal_side'=>'credit','report_type'=>'BS'],
            ['code'=>'2-0003','classification'=>'Prepaid Sales','normal_side'=>'credit','report_type'=>'BS'],
            ['code'=>'2-0004','classification'=>'Tax Payable','normal_side'=>'credit','report_type'=>'BS'],
            
            // --- Equity (BS) ---
            ['code'=>'3-0001','classification'=>'Equity','normal_side'=>'credit','report_type'=>'BS'],
            ['code'=>'3-0002','classification'=>'Current Earning','normal_side'=>'credit','report_type'=>'BS'],
            ['code'=>'3-0003','classification'=>'Retained Earning','normal_side'=>'credit','report_type'=>'BS'],
            
            // --- P/L (PL) ---
            ['code'=>'4-0000','classification'=>'Income','normal_side'=>'credit','report_type'=>'PL'],
            ['code'=>'4-0001','classification'=>'Sales Discount','normal_side'=>'debit','report_type'=>'PL'],
            ['code'=>'5-0000','classification'=>'Cost of Sales','normal_side'=>'debit','report_type'=>'PL'],
            ['code'=>'6-0000','classification'=>'Expense','normal_side'=>'debit','report_type'=>'PL'],
            ['code'=>'7-0000','classification'=>'Other Income','normal_side'=>'credit','report_type'=>'PL'],
            ['code'=>'8-0000','classification'=>'Other Expense','normal_side'=>'debit','report_type'=>'PL'],
            
            // --- Misc (PL) ---
            ['code'=>'9-0001','classification'=>'Rounding Difference','normal_side'=>'credit','report_type'=>'PL'],
            ['code'=>'9-0002','classification'=>'Exchange Profit/Loss','normal_side'=>'Credit','report_type'=>'PL'],
            ['code'=>'9-9999','classification'=>'Profit Loss Summary','normal_side'=>'debit','report_type'=>'NONE'],
            
            ['code'=>'5-0001','classification'=>'Purchase Discount','normal_side'=>'credit','report_type'=>'PL'],

            ['code'=>'1-0008','classification'=>'Tax In','normal_side'=>'debit','report_type'=>'BS'],
            ['code'=>'2-0005','classification'=>'Tax Out','normal_side'=>'credit','report_type'=>'BS'],
        ];

        $rows = array_map(fn($r) => array_merge($r, ['created_at'=>$now,'updated_at'=>$now]), $rows);
    
        // Upsert berdasarkan 'code'
        DB::table('classification_account_type')->upsert(
            $rows,
            ['code'],                                   // unique key
            ['classification','normal_side','report_type','updated_at'] // fields to update on conflict
        );


        $array_simple = [
            ["1", "1-0001", "Cash", "0", "0", "debit", "BS"], // 1
            ["1", "1-0002", "Bank", "0", "0", "debit", "BS"], // 2
            ["1", "1-0003", "Current Asset", "0", "0", "debit", "BS"], // 3
            ["1", "1-0004", "Account Receivable", "0", "0", "debit", "BS"], // 4
            ["1", "1-0005", "Fixed Asset", "0", "0", "debit", "BS"], // 5
            ["1", "1-0006", "Other Asset", "0", "0", "debit", "BS"], // 6
            ["1", "1-0007", "Accumulated Depreciation", "0", "0", "credit", "BS"], // 7
            
            ["2", "2-0001", "Account Payable", "0", "0", "credit", "BS"], // 8
            ["2", "2-0002", "Other Payable", "0", "0", "credit", "BS"], // 9
            ["2", "2-0003", "Prepaid Sales", "0", "0", "credit", "BS"], // 10
            ["2", "2-0004", "Tax Payable", "0", "0", "credit", "BS"], // 11
            
            ["3", "3-0001", "Equity", "0", "0", "credit", "BS"], // 13
            ["3", "3-0002", "Current Earning", "0", "0", "credit", "BS"], // 14
            ["3", "3-0003", "Retained Earning", "0", "0", "credit", "BS"], // 15
            
            ["4", "4-0000", "Income", "0", "0", "credit", "PL"], // 16
            ["4", "4-0001", "Sales Discount", "0", "0", "debit", "PL"], // 17

            ["5", "5-0000", "Cost of Sales", "0", "0", "debit", "PL"], // 18
            ["6", "6-0000", "Expense", "0", "0", "debit", "PL"], // 19
            ["7", "7-0000", "Other Income", "0", "0", "credit", "PL"], // 20
            ["8", "8-0000", "Other Expense", "0", "0", "debit", "PL"], // 21

            ["9", "9-0001", "Rounding Difference", "0", "0", "credit", "PL"], // 22
            ["9", "9-0002", "Exchange Profit/Loss", "0", "0", "credit", "PL"], // 23
            ["9", "9-9999", "Profit Loss Summary", "0", "0", "debit", "NONE"], // 24
            
            ["5", "5-0001", "Purchase Discount", "0", "0", "credit", "PL"], // 25

            ["1", "1-0008", "Tax In", "0", "0", "debit", "BS"], // 26
            ["2", "2-0005", "Tax Out", "0", "0", "credit", "BS"], // 27
        ];

        $length = count($array_simple);
        for ($i=0; $i < $length; $i++) { 
            DB::table('account_type')->insert([
                'classification_id' => $i+1,
                'code' => $array_simple[$i][1],
                'name' => $array_simple[$i][2],
                'cash_flow' => $array_simple[$i][3],
                'can_delete' => $array_simple[$i][4],
                'normal_side' => $array_simple[$i][5],
                'report_type' => $array_simple[$i][6],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
