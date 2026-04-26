<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->text('allergies')->nullable()->after('social_history');

            $table->string('psychiatrist')->nullable()->after('allergies');
            $table->string('cardiologist')->nullable()->after('psychiatrist');
            $table->string('primary_care_provider')->nullable()->after('cardiologist');
            $table->string('pharmacy')->nullable()->after('primary_care_provider');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'allergies',
                'psychiatrist',
                'cardiologist',
                'primary_care_provider',
                'pharmacy',
            ]);
        });
    }
};
