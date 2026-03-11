<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('visits', 'status')) {
            Schema::table('visits', function (Blueprint $table) {
                $table->string('status')->default('scheduled');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('visits', 'status')) {
            Schema::table('visits', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
