<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lab_orders', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('lab_orders', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });
    }
};
