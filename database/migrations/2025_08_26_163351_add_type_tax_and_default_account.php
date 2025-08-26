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
        Schema::table('master_tax', function (Blueprint $table) {
            if (!Schema::hasColumn('master_tax', 'type')) {
                $table->enum('type', ['PPN', 'PPH'])->default('Pph');
            }

            if (!Schema::hasColumn('master_tax', 'account_id')) {
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('id')->on('master_account')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_tax', function (Blueprint $table) {
            if (Schema::hasColumn('master_tax', 'type')) {
                $table->dropColumn('type');
            }
            
            if (Schema::hasColumn('master_tax', 'account_id')) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            }
        });
    }
};
