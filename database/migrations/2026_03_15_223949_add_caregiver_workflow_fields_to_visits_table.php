<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {

            if (!Schema::hasColumn('visits', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('visits', 'visit_started_at')) {
                $table->timestamp('visit_started_at')->nullable();
            }

            if (!Schema::hasColumn('visits', 'visit_completed_at')) {
                $table->timestamp('visit_completed_at')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {

            if (Schema::hasColumn('visits', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('visits', 'visit_started_at')) {
                $table->dropColumn('visit_started_at');
            }

            if (Schema::hasColumn('visits', 'visit_completed_at')) {
                $table->dropColumn('visit_completed_at');
            }

        });
    }
};
