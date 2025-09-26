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
        Schema::table('master_contact', function (Blueprint $table) {
            if (!Schema::hasColumn('master_contact', 'ppn_id')) {
                $table->bigInteger('ppn_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_contact', function (Blueprint $table) {
            if (Schema::hasColumn('master_contact', 'ppn_id')) {
                $table->dropColumn('ppn_id');
            }
        });
    }
};
