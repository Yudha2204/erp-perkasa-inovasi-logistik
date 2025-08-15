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
        Schema::create('group_quotation_m_ex', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_m_ex_id')->nullable()->references('id')->on('quotation_marketing_export')->onDelete('cascade');
            $table->string('group')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_quotation_m_ex');
    }
};
