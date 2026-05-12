<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emar_administrations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medication_id')->constrained()->cascadeOnDelete();
            $table->foreignId('caregiver_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('scheduled_date');
            $table->string('scheduled_time')->nullable();

            $table->string('status')->default('given');
            $table->timestamp('administered_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['facility_id', 'scheduled_date']);
            $table->index(['client_id', 'scheduled_date']);
            $table->index(['medication_id', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emar_administrations');
    }
};
