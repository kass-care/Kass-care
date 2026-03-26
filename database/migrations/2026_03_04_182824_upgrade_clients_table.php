<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('clients', function (Blueprint $table) {

            if (!Schema::hasColumn('clients', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->nullable();
            }

            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable();
            }

            if (!Schema::hasColumn('clients', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }

            if (!Schema::hasColumn('clients', 'diagnosis')) {
                $table->string('diagnosis')->nullable();
            }

        });

    }


    public function down(): void
    {

        Schema::table('clients', function (Blueprint $table) {

            $table->dropColumn([
                'phone',
                'email',
                'address',
                'date_of_birth',
                'diagnosis'
            ]);

        });

    }

};
