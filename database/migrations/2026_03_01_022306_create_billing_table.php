<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('billing', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('visit_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('caregiver_id');

            $table->decimal('hours', 8, 2);
            $table->decimal('rate_per_hour', 8, 2);
            $table->decimal('total_amount', 10, 2);

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing');
    }
};