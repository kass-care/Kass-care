<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();

            $table->foreignId('caregiver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('shift_date');
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();

            $table->string('status')->default('scheduled');

            $table->timestamp('clock_in_at')->nullable();
            $table->timestamp('clock_out_at')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
