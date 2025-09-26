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
        Schema::table('receive_payment_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('receive_payment_detail', 'account_id')) {
                $table->unsignedBigInteger('account_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receive_payment_detail', function (Blueprint $table) {
            if (Schema::hasColumn('receive_payment_detail', 'account_id')) {
                $table->dropColumn('account_id');
            }
        });
    }
};
