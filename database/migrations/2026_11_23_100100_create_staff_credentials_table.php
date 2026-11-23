<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staff credentials: licences, CPR, TB tests, background checks and
 * training, with expiry dates so agencies stay compliant.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff_credentials')) {
            return;
        }

        Schema::create('staff_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('type', 80);
            $table->string('reference', 120)->nullable();
            $table->date('issued_on')->nullable();
            $table->date('expires_on')->nullable()->index();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_credentials');
    }
};
