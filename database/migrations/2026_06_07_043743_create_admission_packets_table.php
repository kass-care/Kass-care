<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_packets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('packet_name')->default('Resident Admission Agreement');
            $table->string('status')->default('draft');

            $table->string('resident_name')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_relationship')->nullable();

            $table->string('monthly_rate')->nullable();
            $table->date('admission_date')->nullable();

            $table->longText('document_text')->nullable();
            $table->longText('signature')->nullable();
            $table->string('signed_by')->nullable();
            $table->timestamp('signed_at')->nullable();

            $table->string('public_token')->nullable()->unique();
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_packets');
    }
};
