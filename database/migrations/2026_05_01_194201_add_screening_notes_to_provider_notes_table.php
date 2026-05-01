<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('provider_notes', 'screening_notes')) {
            Schema::table('provider_notes', function (Blueprint $table) {
                $table->longText('screening_notes')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('provider_notes', 'screening_notes')) {
            Schema::table('provider_notes', function (Blueprint $table) {
                $table->dropColumn('screening_notes');
            });
        }
    }
};
