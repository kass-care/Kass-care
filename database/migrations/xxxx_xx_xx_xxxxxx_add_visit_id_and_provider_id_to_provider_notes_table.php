<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('provider_notes', 'visit_id')) {
                $table->foreignId('visit_id')->nullable()->after('id')->constrained('visits')->nullOnDelete();
            }

            if (!Schema::hasColumn('provider_notes', 'provider_id')) {
                $table->foreignId('provider_id')->nullable()->after('client_id')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('provider_notes', 'follow_up')) {
                $table->text('follow_up')->nullable()->after('plan');
            }

            if (!Schema::hasColumn('provider_notes', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('follow_up');
            }
        });
    }

    public function down(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (Schema::hasColumn('provider_notes', 'visit_id')) {
                $table->dropConstrainedForeignId('visit_id');
            }

            if (Schema::hasColumn('provider_notes', 'provider_id')) {
                $table->dropConstrainedForeignId('provider_id');
            }

            if (Schema::hasColumn('provider_notes', 'follow_up')) {
                $table->dropColumn('follow_up');
            }

            if (Schema::hasColumn('provider_notes', 'signed_at')) {
                $table->dropColumn('signed_at');
            }
        });
    }
};
