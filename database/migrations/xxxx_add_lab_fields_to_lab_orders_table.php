<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lab_orders', function (Blueprint $table) {

            $table->string('lab_type')->after('client_id');

            $table->text('instructions')->nullable()->after('lab_type');

            $table->string('status')->default('pending')->after('instructions');

        });
    }

    public function down()
    {
        Schema::table('lab_orders', function (Blueprint $table) {

            $table->dropColumn('lab_type');
            $table->dropColumn('instructions');
            $table->dropColumn('status');

        });
    }
};
