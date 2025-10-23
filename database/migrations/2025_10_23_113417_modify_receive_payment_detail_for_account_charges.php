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
            // Make invoice_id nullable to allow account-only charges
            $table->unsignedBigInteger('invoice_id')->nullable()->change();
            
            // Add amount field for account-based charges
            if (!Schema::hasColumn('receive_payment_detail', 'amount')) {
                $table->decimal('amount', 30, 2)->nullable();
            }
            
            // Add description field for account-based charges
            if (!Schema::hasColumn('receive_payment_detail', 'description')) {
                $table->text('description')->nullable();
            }
            
            // Add charge_type to distinguish between invoice and account charges
            if (!Schema::hasColumn('receive_payment_detail', 'charge_type')) {
                $table->enum('charge_type', ['invoice', 'account'])->default('invoice');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receive_payment_detail', function (Blueprint $table) {
            // Revert invoice_id to not nullable (if needed)
            $table->unsignedBigInteger('invoice_id')->nullable(false)->change();
            
            // Remove added columns
            if (Schema::hasColumn('receive_payment_detail', 'amount')) {
                $table->dropColumn('amount');
            }
            
            if (Schema::hasColumn('receive_payment_detail', 'description')) {
                $table->dropColumn('description');
            }
            
            if (Schema::hasColumn('receive_payment_detail', 'charge_type')) {
                $table->dropColumn('charge_type');
            }
        });
    }
};
