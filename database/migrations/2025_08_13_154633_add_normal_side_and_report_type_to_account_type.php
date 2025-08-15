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
        Schema::table('account_type', function (Blueprint $table) {
            if (!Schema::hasColumn('account_type', 'normal_side')) {
                $table->enum('normal_side', ['debit', 'credit'])->default('debit');
            }
            if (!Schema::hasColumn('ccount_type', 'report_type')) {
                $table->enum('report_type', ['BS', 'PL', 'NONE'])->default('NONE');  // BS / PL / NONE
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_type', function (Blueprint $table) {
            if (Schema::hasColumn('account_type', 'normal_side')) {
                $table->dropColumn('normal_side');
            }
            if (Schema::hasColumn('account_type', 'report_type')) {
                $table->dropColumn('report_type');
            }
        });
    }
};
