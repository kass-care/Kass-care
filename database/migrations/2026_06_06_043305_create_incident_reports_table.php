<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('visit_id')->nullable()->constrained('visits')->nullOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('incident_type')->nullable();
            $table->string('severity')->default('Low');
            $table->string('location')->nullable();
            $table->timestamp('incident_datetime')->nullable();

            $table->text('description')->nullable();
            $table->text('immediate_action')->nullable();
            $table->string('witnesses')->nullable();

            $table->boolean('provider_notified')->default(false);
            $table->boolean('family_notified')->default(false);
            $table->boolean('ems_called')->default(false);
            $table->boolean('hospital_transfer')->default(false);

            $table->string('status')->default('open');
            $table->text('admin_review_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
