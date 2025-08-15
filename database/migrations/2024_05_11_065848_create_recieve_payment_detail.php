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
        Schema::create('receive_payment_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('head_id')->nullable()->constrained("receive_payment_head")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained("invoice_head")->cascadeOnUpdate()->nullOnDelete();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_nominal',30,2)->nullable();
            $table->string('dp_type')->nullable();
            $table->decimal('dp_nominal',30,2)->nullable();
            $table->foreignId('currency_via_id')->nullable()->constrained("master_exchange")->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('amount_via',30,2)->nullable();
            $table->text('remark')->nullable();            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_payment_detail');
    }
};