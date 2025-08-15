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
        Schema::create('dimension_marketing_import', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_import_id')->nullable()->constrained("marketing_import")->cascadeOnUpdate()->nullOnDelete();
            $table->string('packages')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('input_measure')->nullable();
            $table->string('qty')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimension_marketing_import');
    }
};
