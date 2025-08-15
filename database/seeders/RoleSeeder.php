<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $marketing = Role::create(['name' => 'Marketing']);
        $operation = Role::create(['name' => 'Operation']);
        $finance = Role::create(['name' => 'Finance']);

        $marketing->givePermissionTo([
            'view-export@marketing',
            'create-export@marketing',
            'delete-export@marketing',
            'edit-export@marketing',
            'view-import@marketing',
            'create-import@marketing',
            'delete-import@marketing',
            'edit-import@marketing'
        ]);

        $operation->givePermissionTo([
            'view-export@operation',
            'edit-export@operation',
            'view-import@operation',
            'edit-import@operation',
        ]);

        $accounts = $this->formatPermissions(["contact", "account", "currency", "tax", "term"]);
        $sales = $this->formatPermissions(["sales_order", "invoice", "receive_payment"]);
        $kas = $this->formatPermissions(["kas_in", "kas_out"]);
        $payments = $this->formatPermissions(["account_payable", "payment"]);
        $exchange = ["view-exchange_rate@finance","create-exchange_rate@finance","delete-exchange_rate@finance","edit-exchange_rate@finance"];
        $report = ["view-buku_besar@finance","view-jurnal_umum@finance","view-neraca_saldo@finance","view-arus_kas@finance","view-laba_rugi@finance"];
        
        $allPermissions = array_merge($accounts, $sales, $kas, $payments, $exchange, $report);
        $finance->givePermissionTo($allPermissions);
    }


    private function formatPermissions($items) {
        $formattedPermissions = [];
        foreach ($items as $item) {
            $formattedPermissions[] = "view-{$item}@finance";
            $formattedPermissions[] = "create-{$item}@finance";
            $formattedPermissions[] = "delete-{$item}@finance";
            $formattedPermissions[] = "edit-{$item}@finance";
        }
        return $formattedPermissions;
    }
}
