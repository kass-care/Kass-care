<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('provider_notes', 'status')) {
                $table->string('status')->default('Open')->after('note');
            }

            if (!Schema::hasColumn('provider_notes', 'provider_id')) {
                $table->foreignId('provider_id')->nullable()->after('visit_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('provider_notes', function (Blueprint $table) {
            if (Schema::hasColumn('provider_notes', 'provider_id')) {
                $table->dropConstrainedForeignId('provider_id');
            }

            if (Schema::hasColumn('provider_notes', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
