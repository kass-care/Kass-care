<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facility_provider', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('facility_id');
            $table->unsignedBigInteger('provider_id');

            $table->timestamps();

            $table->unique(['facility_id', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_provider');
    }
};
