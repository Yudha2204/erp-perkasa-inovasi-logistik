<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('balance_account_data', function (Blueprint $table) {
            // Urutan: master_account_id (selectivity tinggi) -> currency_id -> transaction_type_id -> date (range) -> deleted_at
            // Index ini optimal untuk JOIN dengan master_account dan filter by currency, transaction_type, date range
            $table->index(
                ['master_account_id', 'currency_id', 'transaction_type_id', 'date', 'deleted_at'],
                'idx_balance_account_report_main'
            );

            // Index untuk query foreign currency (tanpa master_account_id di awal)
            $table->index(
                ['currency_id', 'transaction_type_id', 'date', 'deleted_at'],
                'idx_balance_account_currency_date'
            );

            // Index untuk query yang filter by master_account_id dan date range
            $table->index(
                ['master_account_id', 'currency_id', 'date', 'deleted_at'],
                'idx_balance_account_account_date'
            );


            $table->dropIndex('balance_account_data_master_account_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balance_account_data', function (Blueprint $table) {
            $table->dropIndex('idx_balance_account_report_main');
            $table->dropIndex('idx_balance_account_currency_date');
            $table->dropIndex('idx_balance_account_account_date');
            $table->dropIndex('idx_balance_account_deleted_at');

            $table->index(
                'master_account_id', 
                'balance_account_data_master_account_id_foreign'
            );
        });
    }
};
