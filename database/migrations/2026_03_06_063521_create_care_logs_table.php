<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('care_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('caregiver_id')->nullable();

            $table->string('meal')->nullable();
            $table->string('bm')->nullable();
            $table->string('shower')->nullable();
            $table->string('medication')->nullable();
            $table->string('mood')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('care_logs');
    }
};
