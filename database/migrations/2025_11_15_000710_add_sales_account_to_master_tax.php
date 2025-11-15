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
        Schema::table('master_tax', function (Blueprint $table) {
            if (!Schema::hasColumn('master_tax', 'sales_account_id')) {
                $table->unsignedBigInteger('sales_account_id')->nullable()->after('account_id');
                $table->foreign('sales_account_id')->references('id')->on('master_account')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_tax', function (Blueprint $table) {
            if (Schema::hasColumn('master_tax', 'sales_account_id')) {
                $table->dropForeign(['sales_account_id']);
                $table->dropColumn('sales_account_id');
            }
        });
    }
};

