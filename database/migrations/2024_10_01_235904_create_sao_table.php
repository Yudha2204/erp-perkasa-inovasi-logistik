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
        Schema::create('sao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('master_contact')->onDelete('cascade');
            $table->foreignId('currency_id')->nullable()->constrained('master_currency')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoice_head')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('account_payable_head')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('master_contact')->onDelete('cascade');
            $table->date('date');
            $table->string('account');
            $table->decimal('total', 15, 2);
            $table->decimal('already_paid', 15, 2);
            $table->decimal('remaining', 15, 2);
            $table->boolean('isPaid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sao');
    }
};
