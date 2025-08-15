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
        Schema::create('sales_order_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('head_id')->nullable()->constrained("sales_order_head")->cascadeOnUpdate()->nullOnDelete();
            $table->text('description')->nullable();            
            $table->integer('quantity')->nullable();
            $table->string('uom')->nullable();
            $table->decimal('price', 30, 2)->nullable();
            $table->text('remark')->nullable();            
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
        Schema::dropIfExists('sales_order_detail');
    }
};
