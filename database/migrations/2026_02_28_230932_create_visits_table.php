<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id')->default(1);

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('caregiver_id');

            $table->date('visit_date');
            $table->time('start_time');
            $table->timestamp('end_time')->nullable();

            $table->string('status')->default('scheduled');

            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');

            $table->foreign('caregiver_id')
                ->references('id')->on('caregivers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
