<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evv', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('visit_id');
            $table->unsignedBigInteger('caregiver_id');

            $table->timestamp('clock_in_time')->nullable();
            $table->timestamp('clock_out_time')->nullable();

            $table->decimal('gps_lat', 10, 7)->nullable();
            $table->decimal('gps_lng', 10, 7)->nullable();

            $table->integer('total_minutes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evv');
    }
};