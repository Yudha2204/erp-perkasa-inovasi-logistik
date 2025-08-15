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
        Schema::create('marketing_export', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained("master_contact")->cascadeOnUpdate()->nullOnDelete();
            $table->string('job_order_id')->unique()->nullable();
            $table->integer('expedition')->nullable();
            $table->integer('transportation')->nullable();
            $table->string('transportation_desc')->nullable();
            $table->string('no_po')->nullable();
            $table->text('description')->nullable();
            $table->string('no_cipl')->nullable();
            $table->string('total_weight')->nullable();
            $table->string('total_volume')->nullable();
            $table->string('freetext_volume')->nullable();
            $table->string('origin')->nullable();
            $table->string('shipper')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('destination')->nullable();
            $table->string('consignee')->nullable();
            $table->string('delivery_address')->nullable();
            $table->text('remark')->nullable();
            $table->integer('status')->nullable();
            $table->string('source')->default('export');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_export');
    }
};
