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
        Schema::create('general_journal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('head_id');
            $table->unsignedBigInteger('account_id');
            $table->text('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('head_id')->references('id')->on('general_journal_heads')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('master_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journal_details');
    }
};
