<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('provider_visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();

            $table->date('visit_date');

            $table->text('notes')->nullable();

            $table->date('next_visit_due')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provider_visits');
    }
};
