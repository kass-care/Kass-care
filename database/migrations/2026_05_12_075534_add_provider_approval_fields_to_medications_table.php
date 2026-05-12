<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            if (!Schema::hasColumn('medications', 'approval_status')) {
                $table->string('approval_status')->default('approved')->after('status');
            }

            if (!Schema::hasColumn('medications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approval_status')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('medications', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            if (!Schema::hasColumn('medications', 'provider_note')) {
                $table->text('provider_note')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            if (Schema::hasColumn('medications', 'provider_note')) {
                $table->dropColumn('provider_note');
            }

            if (Schema::hasColumn('medications', 'approved_at')) {
                $table->dropColumn('approved_at');
            }

            if (Schema::hasColumn('medications', 'approved_by')) {
                $table->dropConstrainedForeignId('approved_by');
            }

            if (Schema::hasColumn('medications', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
