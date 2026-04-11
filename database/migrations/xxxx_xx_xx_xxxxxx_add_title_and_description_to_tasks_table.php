<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'title')) {
                $table->string('title')->nullable();
            }

            if (!Schema::hasColumn('tasks', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('tasks', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
