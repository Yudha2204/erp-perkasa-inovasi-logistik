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
        Schema::create('document_activity_op_im', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_operation_import_id')->nullable()->constrained("activity_operation_import")->cascadeOnUpdate()->nullOnDelete();
            $table->text('document')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_activity_op_im');
    }
};
