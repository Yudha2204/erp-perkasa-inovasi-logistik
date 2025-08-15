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
        Schema::create('sales_order_head', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->integer('number')->nullable();          
            $table->date('date')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->text('description')->nullable();
            $table->integer('marketing_id')->nullable();
            $table->string('source')->nullable();
            $table->decimal('additional_cost', 30, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_nominal', 30, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_head');
    }
};
