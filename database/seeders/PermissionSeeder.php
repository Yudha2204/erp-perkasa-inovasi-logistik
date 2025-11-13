<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['group' => 'role', 'name' => 'create-role@role']);
        Permission::create(['group' => 'role', 'name' => 'edit-role@role']);
        Permission::create(['group' => 'role', 'name' => 'delete-role@role']);
        Permission::create(['group' => 'user', 'name' => 'create-user@user']);
        Permission::create(['group' => 'user', 'name' => 'edit-user@user']);
        Permission::create(['group' => 'user', 'name' => 'delete-user@user']);

        // operation
        Permission::create(['group' => 'operation', 'name' => 'view-export@operation']);
        Permission::create(['group' => 'operation', 'name' => 'edit-export@operation']);

        Permission::create(['group' => 'operation', 'name' => 'view-import@operation']);
        Permission::create(['group' => 'operation', 'name' => 'edit-import@operation']);

        // marketing
        Permission::create(['group' => 'marketing', 'name' => 'view-export@marketing']);
        Permission::create(['group' => 'marketing', 'name' => 'create-export@marketing']);
        Permission::create(['group' => 'marketing', 'name' => 'delete-export@marketing']);
        Permission::create(['group' => 'marketing', 'name' => 'edit-export@marketing']);

        Permission::create(['group' => 'marketing', 'name' => 'view-import@marketing']);
        Permission::create(['group' => 'marketing', 'name' => 'create-import@marketing']);
        Permission::create(['group' => 'marketing', 'name' => 'delete-import@marketing']);
        Permission::create(['group' => 'marketing', 'name' => 'edit-import@marketing']);

        //finance
        $accounts = ["contact", "account", "currency", "tax", "term"];
        foreach($accounts as $account) {
            Permission::create(['group' => 'finance', 'name' => "view-" . $account . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "create-" . $account . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "delete-" . $account . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "edit-" . $account . "@finance"]);
        }

        $sales = ["sales_order", "invoice", "receive_payment"];
        foreach($sales as $sale) {
            Permission::create(['group' => 'finance', 'name' => "view-" . $sale . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "create-" . $sale . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "delete-" . $sale . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "edit-" . $sale . "@finance"]);
        }

        $kas = ["kas_in", "kas_out"];
        foreach($kas as $k) {
            Permission::create(['group' => 'finance', 'name' => "view-" . $k . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "create-" . $k . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "delete-" . $k . "@finance"]);
            Permission::create(['group' => 'finance', 'name' => "edit-" . $k . "@finance"]);
        }

        $payments = ["account_payable", "payment"];
        foreach($payments as $payment) {
            Permission::create(['group' => 'finance', 'name' =>  "view-" . $payment . "@finance"]);
            Permission::create(['group' => 'finance', 'name' =>  "create-" . $payment . "@finance"]);
            Permission::create(['group' => 'finance', 'name' =>  "delete-" . $payment . "@finance"]);
            Permission::create(['group' => 'finance', 'name' =>  "edit-" . $payment . "@finance"]);
        }

        Permission::create(['group' => 'finance', 'name' =>  "view-exchange_rate@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "create-exchange_rate@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "delete-exchange_rate@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "edit-exchange_rate@finance"]);

        Permission::create(['group' => 'finance', 'name' =>  "view-buku_besar@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "view-jurnal_umum@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "view-neraca_saldo@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "view-arus_kas@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "view-laba_rugi@finance"]);
        Permission::create(['group' => 'finance', 'name' =>  "view-outstanding_arap@finance"]);

        Permission::create(['group' => 'process', 'name' => 'view-revaluation@process']);
        Permission::create(['group' => 'process', 'name' => 'execute-revaluation@process']);

        Permission::create(['group' => 'process', 'name' => 'view-pl-closing@process']);
        Permission::create(['group' => 'process', 'name' => 'execute-pl-closing@finance@process']);

        Permission::create(['group' => 'process', 'name' => 'view-annual-pl-closing@process']);
        Permission::create(['group' => 'process', 'name' => 'execute-annual-pl-closing@finance@process']);

        // General Journal permissions
        Permission::create(['group' => 'finance', 'name' => 'view-general_journal@finance']);
        Permission::create(['group' => 'finance', 'name' => 'create-general_journal@finance']);
        Permission::create(['group' => 'finance', 'name' => 'edit-general_journal@finance']);
        Permission::create(['group' => 'finance', 'name' => 'delete-general_journal@finance']);
    }
}
