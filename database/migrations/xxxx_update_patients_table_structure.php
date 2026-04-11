<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            if (!Schema::hasColumn('patients','first_name')) {
                $table->string('first_name')->nullable();
            }

            if (!Schema::hasColumn('patients','last_name')) {
                $table->string('last_name')->nullable();
            }

            if (!Schema::hasColumn('patients','date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }

            if (!Schema::hasColumn('patients','gender')) {
                $table->string('gender')->nullable();
            }

            if (!Schema::hasColumn('patients','room_number')) {
                $table->string('room_number')->nullable();
            }

            if (!Schema::hasColumn('patients','status')) {
                $table->string('status')->default('active');
            }

        });
    }

    public function down(): void
    {
        //
    }
};
