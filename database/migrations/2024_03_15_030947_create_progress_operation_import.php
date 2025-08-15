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
        Schema::create('progress_operation_import', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_import_id')->nullable()->constrained("operation_import")->cascadeOnUpdate()->nullOnDelete();
            $table->date('date_progress')->nullable();            
            $table->time('time_progress')->nullable();
            $table->string('location')->nullable();
            $table->string('location_desc')->nullable();
            $table->integer('transportation')->nullable();
            $table->string('transportation_desc')->nullable();
            $table->string('carrier')->nullable();
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_operation_import');
    }
};
