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
        Schema::create('payment_head', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained("master_account")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->date('date_payment')->nullable();
            $table->integer('number')->nullable();
            $table->text('description')->nullable();
            $table->integer('job_order_id')->nullable();
            $table->string('source')->nullable();
            $table->text('note')->nullable();
            $table->decimal('additional_cost',30,2)->nullable();
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
        Schema::dropIfExists('payment_head');
    }
};
