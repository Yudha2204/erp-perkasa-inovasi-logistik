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
        Schema::create('item_group_quotation_m_im', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_quotation_m_im_id')->nullable()->references('id')->on('group_quotation_m_im')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('total')->nullable();
            $table->text('remark')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_group_quotation_m_im');
    }
};
