<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->nullable()->after('role');
            $table->string('subscription_status')->default('inactive')->after('plan');
            $table->integer('facility_limit')->default(1)->after('subscription_status');
            $table->timestamp('subscription_starts_at')->nullable()->after('facility_limit');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_starts_at');
            $table->string('stripe_customer_id')->nullable()->after('subscription_ends_at');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'subscription_status',
                'facility_limit',
                'subscription_starts_at',
                'subscription_ends_at',
                'stripe_customer_id',
                'stripe_subscription_id',
            ]);
        });
    }
};
