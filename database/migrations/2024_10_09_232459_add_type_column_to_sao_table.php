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
        Schema::table('sao', function (Blueprint $table) {
            $table->string('type')->nullable()->after('isPaid'); // Tambahkan kolom 'type' setelah 'isPaid'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sao', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
