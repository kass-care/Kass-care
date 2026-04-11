<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claim_ledgers', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete()->after('client_id');
            $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete()->after('visit_id');
            $table->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete()->after('facility_id');

            $table->string('payer_name')->nullable()->after('provider_id');
            $table->string('claim_number')->nullable()->after('payer_name');

            $table->date('service_date')->nullable()->after('claim_number');

            $table->decimal('billed_amount', 12, 2)->default(0)->after('service_date');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('billed_amount');
            $table->decimal('adjustment_amount', 12, 2)->default(0)->after('paid_amount');
            $table->decimal('patient_responsibility', 12, 2)->default(0)->after('adjustment_amount');
            $table->decimal('balance_amount', 12, 2)->default(0)->after('patient_responsibility');

            $table->timestamp('paid_at')->nullable()->after('status');
            $table->timestamp('submitted_at')->nullable()->after('paid_at');

            $table->text('notes')->nullable()->after('submitted_at');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('claim_ledgers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn([
                'notes',
                'submitted_at',
                'paid_at',
                'balance_amount',
                'patient_responsibility',
                'adjustment_amount',
                'paid_amount',
                'billed_amount',
                'service_date',
                'claim_number',
                'payer_name',
            ]);
            $table->dropConstrainedForeignId('provider_id');
            $table->dropConstrainedForeignId('facility_id');
            $table->dropConstrainedForeignId('visit_id');
            $table->dropConstrainedForeignId('client_id');
        });
    }
};
