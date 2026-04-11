<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'facility_id')) {
                $table->unsignedBigInteger('facility_id')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'visit_id')) {
                $table->unsignedBigInteger('visit_id')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'title')) {
                $table->string('title')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'description')) {
                $table->text('description')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->string('priority')->default('medium');
            }

            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status')->default('open');
            }

            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->timestamp('due_date')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
