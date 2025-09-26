<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Revaluation transaction type if not exists
        DB::table('transaction_type')->insertOrIgnore([
            'id' => 99,
            'name' => 'Exchange Revaluation',
            'description' => 'Monthly exchange rate revaluation for foreign currency accounts',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove Revaluation transaction type
        DB::table('transaction_type')
            ->where('id', 99)
            ->where('name', 'Exchange Revaluation')
            ->delete();
    }
};
