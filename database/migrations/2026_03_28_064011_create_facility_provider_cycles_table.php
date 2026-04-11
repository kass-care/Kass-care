<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('facility_provider_cycles')) {

            Schema::create('facility_provider_cycles', function (Blueprint $table) {
                $table->id();

                $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
                $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();

                $table->date('last_visit_date')->nullable();
                $table->date('next_due_date')->nullable();

                $table->integer('cycle_days')->default(60);

                $table->string('status')->default('current');

                $table->text('notes')->nullable();

                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_provider_cycles');
    }
};
