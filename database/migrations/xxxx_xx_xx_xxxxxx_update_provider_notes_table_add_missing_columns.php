<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('provider_notes', 'status')) {
            DB::statement("ALTER TABLE provider_notes ADD COLUMN status VARCHAR(255) DEFAULT 'Open'");
        }

        if (!Schema::hasColumn('provider_notes', 'provider_id')) {
            DB::statement("ALTER TABLE provider_notes ADD COLUMN provider_id BIGINT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('provider_notes', 'provider_id')) {
            DB::statement("ALTER TABLE provider_notes DROP COLUMN provider_id");
        }

        if (Schema::hasColumn('provider_notes', 'status')) {
            DB::statement("ALTER TABLE provider_notes DROP COLUMN status");
        }
    }
};
