<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('plan')->nullable()->after('location');
            $table->string('subscription_status')->default('inactive')->after('plan');
            $table->integer('provider_limit')->default(1)->after('subscription_status');
            $table->integer('caregiver_limit')->default(5)->after('provider_limit');
            $table->timestamp('subscription_starts_at')->nullable()->after('caregiver_limit');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_starts_at');
            $table->string('stripe_id')->nullable()->after('subscription_ends_at');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_id');
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'subscription_status',
                'provider_limit',
                'caregiver_limit',
                'subscription_starts_at',
                'subscription_ends_at',
                'stripe_id',
                'stripe_subscription_id',
            ]);
        });
    }
};
