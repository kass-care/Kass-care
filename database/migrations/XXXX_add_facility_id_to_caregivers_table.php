<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caregivers', function (Blueprint $table) {
            $table->unsignedBigInteger('facility_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('caregivers', function (Blueprint $table) {
            $table->dropColumn('facility_id');
        });
    }
};
