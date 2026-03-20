<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            $table->text('adl_notes')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            $table->dropColumn('adl_notes');
        });
    }
};
