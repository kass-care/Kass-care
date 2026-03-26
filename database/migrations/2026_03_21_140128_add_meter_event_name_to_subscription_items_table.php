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
        Schema::table('visits', function (Blueprint $table) {
            // Safety check: only add columns if they don't exist
            if (!Schema::hasColumn('visits', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('visits', 'adls')) {
                $table->json('adls')->nullable();
            }
            if (!Schema::hasColumn('visits', 'vitals')) {
                $table->json('vitals')->nullable();
            }
            if (!Schema::hasColumn('visits', 'check_in')) {
                $table->timestamp('check_in')->nullable();
            }
            if (!Schema::hasColumn('visits', 'check_out')) {
                $table->timestamp('check_out')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn(['notes', 'adls', 'vitals', 'check_in', 'check_out']);
        });
    }
};
