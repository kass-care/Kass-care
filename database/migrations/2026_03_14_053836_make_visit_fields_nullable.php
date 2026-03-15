<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert start_time from TIME → TIMESTAMP safely
        DB::statement("
            ALTER TABLE visits
            ALTER COLUMN start_time TYPE timestamp(0) without time zone
            USING ('1970-01-01'::date + start_time)
        ");
        DB::statement('ALTER TABLE visits ALTER COLUMN start_time DROP NOT NULL');

        // Make other datetime fields nullable
        DB::statement('ALTER TABLE visits ALTER COLUMN end_time DROP NOT NULL');
        DB::statement('ALTER TABLE visits ALTER COLUMN visit_started DROP NOT NULL');
        DB::statement('ALTER TABLE visits ALTER COLUMN visit_completed DROP NOT NULL');
        DB::statement('ALTER TABLE visits ALTER COLUMN check_in_time DROP NOT NULL');
    }

    public function down(): void
    {
        // Revert back (adjust types as needed)
        // Note: reversing TIME → TIMESTAMP conversion may lose date info
    }
};
