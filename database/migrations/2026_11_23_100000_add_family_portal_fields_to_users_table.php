<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Family portal: a family member is a user with role "family"
 * who is linked to one client (the person receiving care).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->index();
            }
            if (!Schema::hasColumn('users', 'relationship')) {
                $table->string('relationship', 60)->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 40)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['client_id', 'relationship'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
