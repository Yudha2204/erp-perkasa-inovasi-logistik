<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('balance_account_data', function (Blueprint $table) {
            if (!Schema::hasColumn('balance_account_data', 'currency_id')) {
                $table->unsignedBigInteger('currency_id')->nullable();
                $table->foreign('currency_id')->references('id')->on('master_currency')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balance_account', function (Blueprint $table) {
            if (Schema::hasColumn('balance_account_data', 'currency_id')) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            }
        });
    }
};
