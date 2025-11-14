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
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7)->unique(); // Format: YYYY-MM (e.g., 2025-01)
            $table->date('start_date'); // First day of the period
            $table->date('end_date'); // Last day of the period
            $table->enum('status', ['open', 'closed'])->default('open'); // Period status
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamp('closed_at')->nullable(); // When the period was closed
            $table->unsignedBigInteger('closed_by')->nullable(); // User who closed the period
            $table->timestamps();
            $table->softDeletes();

            $table->index('period');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
            $table->foreign('closed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscal_periods');
    }
};

