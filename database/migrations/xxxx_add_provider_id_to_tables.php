<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable()->after('id');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable()->after('id');
        });

        Schema::table('caregivers', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable()->after('id');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('provider_id');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('provider_id');
        });

        Schema::table('caregivers', function (Blueprint $table) {
            $table->dropColumn('provider_id');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn('provider_id');
        });
    }
};
