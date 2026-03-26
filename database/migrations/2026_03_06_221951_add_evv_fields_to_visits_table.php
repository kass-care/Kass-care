<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->boolean('visit_started')->default(false);
            $table->boolean('visit_completed')->default(false);

        });
    }

    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {

            $table->dropColumn('started_at');
            $table->dropColumn('ended_at');
            $table->dropColumn('visit_started');
            $table->dropColumn('visit_completed');

        });
    }
};
