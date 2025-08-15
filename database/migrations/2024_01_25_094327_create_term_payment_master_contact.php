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
        Schema::create('term_payment_master_contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_payment_id')->nullable()->constrained("master_term_of_payment")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('term_payment_master_contact');
    }
};
