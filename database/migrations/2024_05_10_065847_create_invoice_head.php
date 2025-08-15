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
        Schema::create('invoice_head', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('sales_id')->nullable()->constrained('sales_order_head')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('term_payment')->nullable()->constrained('master_term_of_payment')->cascadeOnUpdate()->nullOnDelete();  
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete(); 
            $table->integer('number')->nullable();   
            $table->date('date_invoice')->nullable();
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
        Schema::dropIfExists('invoice_head');
    }
};
