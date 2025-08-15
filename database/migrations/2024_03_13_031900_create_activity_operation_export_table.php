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
        Schema::create('activity_operation_export', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_export_id')->nullable()->constrained("operation_export")->cascadeOnUpdate()->nullOnDelete();
            $table->date('batam_entry_date')->nullable();
            $table->date('batam_exit_date')->nullable();
            $table->date('destination_entry_date')->nullable();
            $table->date('warehouse_entry_date')->nullable();
            $table->date('warehouse_exit_date')->nullable();
            $table->date('client_received_date')->nullable();
            $table->date('sin_entry_date')->nullable();
            $table->date('sin_exit_date')->nullable();
            $table->date('return_pod_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_operation_export');
    }
};
