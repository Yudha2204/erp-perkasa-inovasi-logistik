<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'SuperAdmin', 
            'username' => 'superadmin', 
            'email' => 'superadmin@pil.com',
            'department' => 'SuperAdmin',
            'password' => Hash::make('123456')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Uncomment this if you need to seed the other user
        // $piljkt = User::create([
        //     'name' => 'PILJKT', 
        //     'username' => 'piljkt', 
        //     'email' => 'piljkt@pil.com',
        //     'department' => 'SuperAdmin',
        //     'password' => Hash::make('password123**w')
        // ]);
        // $piljkt->assignRole('Super Admin');
        // $pilbth = User::create([
        //     'name' => 'PILBTH', 
        //     'username' => 'pilbth', 
        //     'email' => 'pilbth@pil.com',
        //     'department' => 'SuperAdmin',
        //     'password' => Hash::make('password123**e')
        // ]);
        // $pilbth->assignRole('Super Admin');

        // // Creating Marketing
        // $marketing1 = User::create([
        //     'name' => 'Marketing 1', 
        //     'username' => 'mrkt1', 
        //     'email' => 'mrkt1@pil.com',
        //     'department' => 'Marketing',
        //     'password' => Hash::make('mrkt1**q')
        // ]);
        // $marketing1->assignRole('Marketing');
        // $marketing2 = User::create([
        //     'name' => 'Marketing 2', 
        //     'username' => 'mrkt2', 
        //     'email' => 'mrkt2@pil.com',
        //     'department' => 'Marketing',
        //     'password' => Hash::make('mrkt2**w')
        // ]);
        // $marketing2->assignRole('Marketing');

        // // Creating Operation
        // $operation1 = User::create([
        //     'name' => 'Operation 1', 
        //     'username' => 'ops1', 
        //     'email' => 'ops1@pil.com',
        //     'department' => 'Operation',
        //     'password' => Hash::make('ops1**e')
        // ]);
        // $operation1->assignRole('Operation');
        // $operation2 = User::create([
        //     'name' => 'Operation 2', 
        //     'username' => 'ops2', 
        //     'email' => 'ops2@pil.com',
        //     'department' => 'Operation',
        //     'password' => Hash::make('ops2**r')
        // ]);
        // $operation2->assignRole('Operation');

        // // Creating Finance
        // $finance1 = User::create([
        //     'name' => 'Finance 1', 
        //     'username' => 'fnc1', 
        //     'email' => 'fnc1@pil.com',
        //     'department' => 'Finance',
        //     'password' => Hash::make('fnc1**t')
        // ]);
        // $finance1->assignRole('Finance');
        // $finance2 = User::create([
        //     'name' => 'Finance 2', 
        //     'username' => 'fnc2', 
        //     'email' => 'fnc2@pil.com',
        //     'department' => 'Finance',
        //     'password' => Hash::make('fnc2**y')
        // ]);
        // $finance2->assignRole('Finance');
    }
}
