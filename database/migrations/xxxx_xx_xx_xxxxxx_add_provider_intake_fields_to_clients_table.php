<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'gender')) {
                $table->string('gender')->nullable()->after('date_of_birth');
            }

            if (!Schema::hasColumn('clients', 'height')) {
                $table->decimal('height', 5, 2)->nullable()->after('room');
            }

            if (!Schema::hasColumn('clients', 'weight')) {
                $table->decimal('weight', 5, 2)->nullable()->after('height');
            }

            if (!Schema::hasColumn('clients', 'bmi')) {
                $table->decimal('bmi', 5, 2)->nullable()->after('weight');
            }

            if (!Schema::hasColumn('clients', 'chief_complaint')) {
                $table->text('chief_complaint')->nullable()->after('diagnosis');
            }

            if (!Schema::hasColumn('clients', 'medical_history')) {
                $table->text('medical_history')->nullable()->after('chief_complaint');
            }

            if (!Schema::hasColumn('clients', 'family_history')) {
                $table->text('family_history')->nullable()->after('medical_history');
            }

            if (!Schema::hasColumn('clients', 'social_history')) {
                $table->text('social_history')->nullable()->after('family_history');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'gender',
                'height',
                'weight',
                'bmi',
                'chief_complaint',
                'medical_history',
                'family_history',
                'social_history',
            ] as $column) {
                if (Schema::hasColumn('clients', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
