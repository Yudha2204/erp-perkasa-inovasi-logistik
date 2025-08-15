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
        Schema::create('account_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification_id')->nullable()->constrained("classification_account_type")->cascadeOnUpdate()->nullOnDelete();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('cash_flow')->nullable();
            $table->tinyInteger('can_delete')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_type');
    }
};
