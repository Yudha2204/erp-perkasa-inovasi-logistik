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
        Schema::create('operation_export', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_export_id')->nullable()->constrained("marketing_export")->cascadeOnUpdate()->nullOnDelete();
            $table->string('job_order_id')->unique()->nullable();
            $table->string('origin')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('pickup_address_desc')->nullable();
            $table->date('pickup_date')->nullable();
            $table->integer('transportation')->nullable();
            $table->string('transportation_desc')->nullable();
            $table->date('departure_date')->nullable();
            $table->time('departure_time')->nullable();

            $table->string('destination')->nullable();
            $table->date('arrival_date')->nullable();
            $table->time('arrival_time')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_address_desc')->nullable();
            $table->string('recepient_name')->nullable();
            $table->string('arrival_desc')->nullable();
            $table->text('remark')->nullable();
            $table->integer('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_export');
    }
};
