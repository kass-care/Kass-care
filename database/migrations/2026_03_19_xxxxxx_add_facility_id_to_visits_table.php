<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('visits', 'facility_id')) {
            Schema::table('visits', function (Blueprint $table) {
                $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('visits', 'facility_id')) {
            Schema::table('visits', function (Blueprint $table) {
                $table->dropConstrainedForeignId('facility_id');
            });
        }
    }
};
