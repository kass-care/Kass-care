<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('provider_notes', 'chief_complaint')) {
                $table->text('chief_complaint')->nullable()->after('provider_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (Schema::hasColumn('provider_notes', 'chief_complaint')) {
                $table->dropColumn('chief_complaint');
            }
        });
    }
};
