<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_packet_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_packet_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('document_template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');
            $table->string('category')->nullable();
            $table->longText('content');
            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_packet_documents');
    }
};
