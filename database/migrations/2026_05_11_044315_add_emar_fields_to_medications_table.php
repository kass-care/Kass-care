<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->string('route')->nullable()->after('dose');
            $table->boolean('is_prn')->default(false)->after('frequency');
            $table->json('emar_times')->nullable()->after('is_prn');
            $table->date('start_date')->nullable()->after('emar_times');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn([
                'route',
                'is_prn',
                'emar_times',
                'start_date',
                'end_date',
            ]);
        });
    }
};
