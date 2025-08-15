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
        Schema::create('kas_out_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('head_id')->nullable()->constrained("kas_out_head")->cascadeOnUpdate()->nullOnDelete();
            $table->text('description')->nullable();
            $table->foreignId('account_id')->nullable()->constrained("master_account")->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('total',30,2)->nullable();
            $table->text('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_out_detail');
    }
};
