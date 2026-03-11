<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('schedules', function (Blueprint $table) {

            if (!Schema::hasColumn('schedules','visit_date')) {
                $table->date('visit_date')->nullable();
            }

            if (!Schema::hasColumn('schedules','start_time')) {
                $table->time('start_time')->nullable();
            }

            if (!Schema::hasColumn('schedules','end_time')) {
                $table->time('end_time')->nullable();
            }

        });

    }



    public function down(): void
    {

        Schema::table('schedules', function (Blueprint $table) {

            $table->dropColumn([
                'visit_date',
                'start_time',
                'end_time'
            ]);

        });

    }

};
