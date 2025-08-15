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
        Schema::create('quotation_marketing_import', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_import_id')->nullable()->constrained("marketing_import")->cascadeOnUpdate()->nullOnDelete();
            $table->date('date')->nullable();
            $table->string('quotation_no')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('project_desc')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
            $table->string('sales_value')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_marketing_import');
    }
};
