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
        Schema::table('master_account', function (Blueprint $table) {
            if (!Schema::hasColumn('master_account', 'type')) {
                $table->enum('type', ['header', 'detail'])->default('detail');
            }
            if (!Schema::hasColumn('master_account', 'parent')) {
                $table->unsignedBigInteger('parent')->nullable()->after('type');
                $table->foreign('parent')->references('id')->on('master_account')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_account', function (Blueprint $table) {
            if (Schema::hasColumn('master_account', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('master_account', 'parent')) {
                $table->dropForeign(['parent']);
                $table->dropColumn('parent');
            }
        });
    }
};
