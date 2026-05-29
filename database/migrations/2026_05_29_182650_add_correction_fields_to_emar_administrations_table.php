<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emar_administrations', function (Blueprint $table) {
            $table->boolean('is_corrected')->default(false)->after('notes');
            $table->string('previous_status')->nullable()->after('is_corrected');
            $table->text('previous_notes')->nullable()->after('previous_status');
            $table->text('correction_reason')->nullable()->after('previous_notes');
            $table->foreignId('corrected_by')->nullable()->after('correction_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('corrected_at')->nullable()->after('corrected_by');
        });
    }

    public function down(): void
    {
        Schema::table('emar_administrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('corrected_by');
            $table->dropColumn([
                'is_corrected',
                'previous_status',
                'previous_notes',
                'correction_reason',
                'corrected_at',
            ]);
        });
    }
};
