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
        Schema::create('master_exchange', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignId('from_currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('from_nominal',30,2)->nullable();
            $table->foreignId('to_currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('to_nominal',30,2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_exchange');
    }
};
