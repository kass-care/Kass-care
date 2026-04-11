<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('name');
            $table->string('gender')->nullable()->after('dob');

            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();

            $table->text('chief_complaint')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('social_history')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'dob',
                'gender',
                'height',
                'weight',
                'bmi',
                'chief_complaint',
                'medical_history',
                'family_history',
                'social_history',
            ]);
        });
    }
};
