<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('care_logs', 'alerts_reviewed_at')) {
                $table->timestamp('alerts_reviewed_at')->nullable()->after('updated_at');
            }

            if (!Schema::hasColumn('care_logs', 'alerts_reviewed_by')) {
                $table->unsignedBigInteger('alerts_reviewed_by')->nullable()->after('alerts_reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('care_logs', 'alerts_reviewed_at')) {
                $columns[] = 'alerts_reviewed_at';
            }

            if (Schema::hasColumn('care_logs', 'alerts_reviewed_by')) {
                $columns[] = 'alerts_reviewed_by';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
