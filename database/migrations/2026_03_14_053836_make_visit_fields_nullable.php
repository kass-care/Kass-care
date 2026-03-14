<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {

            // Allow these fields to be nullable
            $table->timestamp('start_time')->nullable()->change();
            $table->timestamp('end_time')->nullable()->change();
            $table->timestamp('check_in_time')->nullable()->change();
            $table->timestamp('check_out_time')->nullable()->change();

            $table->string('check_in_latitude')->nullable()->change();
            $table->string('check_in_longitude')->nullable()->change();
            $table->string('check_out_latitude')->nullable()->change();
            $table->string('check_out_longitude')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {

            // Revert fields back to NOT NULL
            $table->timestamp('start_time')->nullable(false)->change();
            $table->timestamp('end_time')->nullable(false)->change();
            $table->timestamp('check_in_time')->nullable(false)->change();
            $table->timestamp('check_out_time')->nullable(false)->change();

            $table->string('check_in_latitude')->nullable(false)->change();
            $table->string('check_in_longitude')->nullable(false)->change();
            $table->string('check_out_latitude')->nullable(false)->change();
            $table->string('check_out_longitude')->nullable(false)->change();

        });
    }
};
