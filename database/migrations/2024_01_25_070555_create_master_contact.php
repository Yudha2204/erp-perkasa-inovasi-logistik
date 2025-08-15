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
        Schema::create('master_contact', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('title')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp_ktp')->nullable();
            $table->text('document')->nullable();
            $table->string('type')->nullable();
            //company
            $table->string('company_name')->nullable();
            $table->integer('type_of_company')->nullable();
            $table->integer('company_tax_status')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('acc_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('swift_code')->nullable();
            //address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            //others
            $table->string('pic_for_urgent_status')->nullable();
            $table->string('mobile_number')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_contact');
    }
};
