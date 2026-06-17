<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_administrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medication_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('caregiver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('administration_date');

            $table->string('scheduled_time')->nullable();

            $table->timestamp('administered_at')->nullable();

            $table->enum('status', [
                'scheduled',
                'given',
                'missed',
                'refused',
                'held',
                'prn'
            ])->default('scheduled');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_administrations');
    }
};
