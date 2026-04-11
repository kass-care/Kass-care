<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {

            $table->boolean('accepted_terms')->default(false);
            $table->timestamp('accepted_terms_at')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {

            $table->dropColumn([
                'accepted_terms',
                'accepted_terms_at'
            ]);

        });
    }
};
