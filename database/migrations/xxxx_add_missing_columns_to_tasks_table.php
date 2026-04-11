<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {

            if (!Schema::hasColumn('tasks', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('tasks', 'visit_id')) {
                $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('tasks', 'facility_id')) {
                $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('tasks', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('tasks', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
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
        });
    }

    public function down(): void
    {
        //
    }
};
