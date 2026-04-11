<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diagnosis_id')->nullable()->constrained()->nullOnDelete();

            $table->string('medication_name');
            $table->string('dose')->nullable();
            $table->string('frequency')->nullable();
            $table->text('instructions')->nullable();

            $table->foreignId('prescribed_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
