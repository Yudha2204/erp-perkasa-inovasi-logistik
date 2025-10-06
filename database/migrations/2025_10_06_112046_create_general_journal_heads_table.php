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
        Schema::create('general_journal_heads', function (Blueprint $table) {
            $table->id();
            $table->string('journal_number')->unique();
            $table->date('date');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('currency_id')->references('id')->on('master_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journal_heads');
    }
};
