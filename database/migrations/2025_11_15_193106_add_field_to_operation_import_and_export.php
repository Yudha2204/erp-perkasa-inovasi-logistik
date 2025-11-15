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
        Schema::table('operation_import', function (Blueprint $table) {
            if (!Schema::hasColumn('operation_import', 'mbl')) {
                $table->string('mbl')->nullable();
            }
            if (!Schema::hasColumn('operation_import', 'hbl')) {
                $table->string('hbl')->nullable();
            }
            if (!Schema::hasColumn('operation_import', 'chargeable_weight')) {
                $table->decimal('chargeable_weight', 10, 2)->nullable();
            }
        });
        Schema::table('operation_export', function (Blueprint $table) {
            if (!Schema::hasColumn('operation_export', 'mbl')) {
                $table->string('mbl')->nullable();
            }
            if (!Schema::hasColumn('operation_export', 'hbl')) {
                $table->string('hbl')->nullable();
            }
             if (!Schema::hasColumn('operation_export', 'chargeable_weight')) {
                $table->decimal('chargeable_weight', 10, 2)->nullable();
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_import', function (Blueprint $table) {
            //
            if (Schema::hasColumn('operation_import', 'mbl')) {
                $table->dropColumn('mbl');
            }
            if (Schema::hasColumn('operation_import', 'hbl')) {
                $table->dropColumn('hbl');
            }
            if (Schema::hasColumn('operation_import', 'chargeable_weight')) {
                $table->dropColumn('chargeable_weight');
            }

        });
        Schema::table('operation_export', function (Blueprint $table) {
            //
            if (Schema::hasColumn('operation_export', 'mbl')) {
                $table->dropColumn('mbl');
            }
            if (Schema::hasColumn('operation_export', 'hbl')) {
                $table->dropColumn('hbl');
            }
             if (Schema::hasColumn('operation_export', 'chargeable_weight')) {
                $table->dropColumn('chargeable_weight');
            }

        });
    }
};
