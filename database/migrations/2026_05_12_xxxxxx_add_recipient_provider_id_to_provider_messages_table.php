<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('provider_messages', 'recipient_provider_id')) {
                $table->foreignId('recipient_provider_id')
                    ->nullable()
                    ->after('provider_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('provider_messages', function (Blueprint $table) {
            if (Schema::hasColumn('provider_messages', 'recipient_provider_id')) {
                $table->dropConstrainedForeignId('recipient_provider_id');
            }
        });
    }
};
