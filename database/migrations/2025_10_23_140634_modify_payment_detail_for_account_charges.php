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
        Schema::table('payment_detail', function (Blueprint $table) {
            // Make payable_id nullable to support account charges
            $table->unsignedBigInteger('payable_id')->nullable()->change();
            
            // Add account charge fields
            if (!Schema::hasColumn('payment_detail', 'amount')) {
                $table->decimal('amount', 30, 2)->nullable();
            }
            if (!Schema::hasColumn('payment_detail', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('payment_detail', 'charge_type')) {
                $table->enum('charge_type', ['payable', 'account'])->default('payable');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_detail', function (Blueprint $table) {
            // Revert payable_id to not nullable
            $table->unsignedBigInteger('payable_id')->nullable(false)->change();
            
            // Drop account charge fields
            if (Schema::hasColumn('payment_detail', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('payment_detail', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('payment_detail', 'charge_type')) {
                $table->dropColumn('charge_type');
            }
        });
    }
};