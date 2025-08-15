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
        Schema::create('master_bank_currency', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->string('account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('address')->nullable();
            $table->string('swift_code')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_bank_currency');
    }
};
