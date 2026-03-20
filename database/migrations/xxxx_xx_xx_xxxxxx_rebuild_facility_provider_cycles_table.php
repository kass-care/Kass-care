<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('facility_provider_cycles');

        Schema::create('facility_provider_cycles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('facility_id');
            $table->unsignedBigInteger('provider_id');

            $table->integer('review_interval_days')->default(60);

            $table->timestamp('last_completed_at')->nullable();
            $table->timestamp('next_due_at')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('completed_for_cycle_at')->nullable();

            $table->string('status')->default('current');
            $table->string('priority')->default('normal');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('facility_id')
                ->references('id')
                ->on('facilities')
                ->cascadeOnDelete();

            $table->foreign('provider_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_provider_cycles');
    }
};
