<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('visit_caregiver');

        Schema::create('visit_caregiver', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
            $table->foreignId('caregiver_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['visit_id', 'caregiver_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_caregiver');
    }
};
