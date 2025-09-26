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
        Schema::table('invoice_head', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_head', 'account_id')) {
                $table->unsignedBigInteger('account_id')->nullable();
            }
            if (!Schema::hasColumn('invoice_head', 'tax_id')) {
                $table->unsignedBigInteger('tax_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_head', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_head', 'account_id')) {
                $table->dropColumn('account_id');
            }
            if (Schema::hasColumn('tax_id', 'tax_id')) {
                $table->dropColumn('tax_id');
            }
        });
    }
};
