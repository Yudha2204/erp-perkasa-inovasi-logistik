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
        Schema::create('kas_out_head', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained("master_account")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->integer('job_order_id')->nullable();
            $table->string('source')->nullable();
            $table->date('date_kas_out')->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained("no_transaction_kas_out")->cascadeOnUpdate()->nullOnDelete();  
            $table->integer('number')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_out_head');
    }
};
