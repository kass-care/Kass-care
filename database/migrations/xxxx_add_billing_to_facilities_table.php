<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {

            $table->string('stripe_id')->nullable();
            $table->string('subscription_status')->default('inactive');
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->string('plan')->nullable();
            $table->integer('facility_limit')->default(1);

        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {

            $table->dropColumn([
                'stripe_id',
                'subscription_status',
                'subscription_starts_at',
                'subscription_ends_at',
                'plan',
                'facility_limit'
            ]);

        });
    }
};
