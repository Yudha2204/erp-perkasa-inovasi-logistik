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
        Schema::create('notification_to_role', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->string('remark')->nullable();
            $table->text('content')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_to_role');
    }
};
