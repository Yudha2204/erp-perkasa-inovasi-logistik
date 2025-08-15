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
        Schema::create('balance_account_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_account_id')->nullable()->constrained("master_account")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('transaction_type_id')->nullable()->constrained("transaction_type")->cascadeOnUpdate()->nullOnDelete();
            $table->integer('transaction_id')->nullable();
            $table->decimal('debit', 30, 2)->nullable();
            $table->decimal('credit', 30, 2)->nullable();
            $table->date('date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_account_data');
    }
};
