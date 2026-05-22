<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->foreignId('readiness_item_id')->nullable()->constrained('readiness_items')->nullOnDelete();

            $table->string('title');
            $table->string('category')->nullable();
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();

            $table->date('expires_at')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_documents');
    }
};
