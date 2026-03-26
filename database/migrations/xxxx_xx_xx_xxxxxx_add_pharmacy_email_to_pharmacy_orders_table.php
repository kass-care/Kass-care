<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacy_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pharmacy_orders', 'pharmacy_email')) {
                $table->string('pharmacy_email')->nullable()->after('pharmacy_fax');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pharmacy_orders', function (Blueprint $table) {
            if (Schema::hasColumn('pharmacy_orders', 'pharmacy_email')) {
                $table->dropColumn('pharmacy_email');
            }
        });
    }
};
