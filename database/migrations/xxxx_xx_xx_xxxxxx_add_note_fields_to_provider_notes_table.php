<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('provider_notes', 'subjective')) {
                $table->text('subjective')->nullable();
            }

            if (!Schema::hasColumn('provider_notes', 'objective')) {
                $table->text('objective')->nullable();
            }

            if (!Schema::hasColumn('provider_notes', 'assessment')) {
                $table->text('assessment')->nullable();
            }

            if (!Schema::hasColumn('provider_notes', 'plan')) {
                $table->text('plan')->nullable();
            }

            if (!Schema::hasColumn('provider_notes', 'follow_up')) {
                $table->text('follow_up')->nullable();
            }

            if (!Schema::hasColumn('provider_notes', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }

            if (!Schema::hasColumn('provider_notes', 'visit_id')) {
                $table->foreignId('visit_id')->nullable()->constrained('visits')->nullOnDelete();
            }

            if (!Schema::hasColumn('provider_notes', 'provider_id')) {
                $table->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (Schema::hasColumn('provider_notes', 'subjective')) {
                $table->dropColumn('subjective');
            }

            if (Schema::hasColumn('provider_notes', 'objective')) {
                $table->dropColumn('objective');
            }

            if (Schema::hasColumn('provider_notes', 'assessment')) {
                $table->dropColumn('assessment');
            }

            if (Schema::hasColumn('provider_notes', 'plan')) {
                $table->dropColumn('plan');
            }

            if (Schema::hasColumn('provider_notes', 'follow_up')) {
                $table->dropColumn('follow_up');
            }

            if (Schema::hasColumn('provider_notes', 'signed_at')) {
                $table->dropColumn('signed_at');
            }

            if (Schema::hasColumn('provider_notes', 'visit_id')) {
                $table->dropConstrainedForeignId('visit_id');
            }

            if (Schema::hasColumn('provider_notes', 'provider_id')) {
                $table->dropConstrainedForeignId('provider_id');
            }
        });
    }
};
