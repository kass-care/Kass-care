<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the table exists before trying to modify it
        if (Schema::hasTable('care_logs')) {
            Schema::table('care_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('care_logs', 'visit_id')) {
                    $table->bigInteger('visit_id')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('care_logs')) {
            Schema::table('care_logs', function (Blueprint $table) {
                if (Schema::hasColumn('care_logs', 'visit_id')) {
                    $table->dropColumn('visit_id');
                }
            });
        }
    }
};
