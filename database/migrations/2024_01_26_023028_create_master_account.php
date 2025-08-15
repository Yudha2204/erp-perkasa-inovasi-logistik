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
        Schema::create('master_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_type_id')->nullable()->constrained("account_type")->cascadeOnUpdate()->nullOnDelete();
            $table->string('code')->nullable();
            $table->string('account_name')->nullable();
            $table->foreignId('master_currency_id')->nullable()->constrained("master_currency")->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('master_account');
    }
};
