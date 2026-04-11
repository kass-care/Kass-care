<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'facility_id')) {
                $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin');
            }

            if (!Schema::hasColumn('users', 'plan')) {
                $table->string('plan')->nullable();
            }

            if (!Schema::hasColumn('users', 'subscription_status')) {
                $table->string('subscription_status')->default('pending');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'facility_id')) {
                $table->dropConstrainedForeignId('facility_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'plan')) {
                $table->dropColumn('plan');
            }

            if (Schema::hasColumn('users', 'subscription_status')) {
                $table->dropColumn('subscription_status');
            }
        });
    }
};
