<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // If you want clients per organization:
            $table->unsignedBigInteger('organization_id')->nullable();

            $table->string('name');
            $table->integer('age')->nullable();
            $table->string('room')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Optional foreign key (only if organizations table exists)
            $table->foreign('organization_id')
                  ->references('id')->on('organizations')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
