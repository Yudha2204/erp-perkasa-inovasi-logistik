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
        Schema::create('vendor_operation_export', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_export_id')->nullable()->constrained("operation_export")->cascadeOnUpdate()->nullOnDelete();
            $table->integer('vendor')->nullable();
            $table->string('total_charge')->nullable();
            $table->string('transit')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_operation_export');
    }
};
