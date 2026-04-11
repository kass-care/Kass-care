<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->string('priority')->default('medium')->after('description');
            }

            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status')->default('open')->after('priority');
            }

            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->timestamp('due_date')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'facility_id')) {
                $table->unsignedBigInteger('facility_id')->nullable();
            }

        });
    }

    public function down(): void
    {
        // safe rollback not required now
    }
};
