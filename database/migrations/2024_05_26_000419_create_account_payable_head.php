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
        Schema::create('account_payable_head', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete(); 
            $table->integer('operation_id')->nullable();
            $table->string('source')->nullable();
            $table->integer('transit_via')->nullable();
            $table->string('transaction')->unique()->nullable();   
            $table->date('date_order')->nullable();
            $table->text('description')->nullable();
            $table->decimal('additional_cost',30,2)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_nominal',30,2)->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_payable_head');
    }
};
