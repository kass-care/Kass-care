<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('care_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();

            $table->text('note');
            $table->string('vitals')->nullable();   // e.g., BP 120/80
            $table->string('medication')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('care_notes');
    }
};