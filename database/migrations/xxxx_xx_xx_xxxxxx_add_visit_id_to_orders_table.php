<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'visit_id')) {
                $table->foreignId('visit_id')
                    ->nullable()
                    ->constrained('visits')
                    ->nullOnDelete()
                    ->after('client_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'visit_id')) {
                $table->dropConstrainedForeignId('visit_id');
            }
        });
    }
};
