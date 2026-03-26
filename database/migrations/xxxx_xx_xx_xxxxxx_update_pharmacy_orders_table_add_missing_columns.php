<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pharmacy_orders')) {
            return;
        }

        Schema::table('pharmacy_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pharmacy_orders', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'provider_id')) {
                $table->unsignedBigInteger('provider_id')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'medication_name')) {
                $table->string('medication_name')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'dosage')) {
                $table->string('dosage')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'frequency')) {
                $table->string('frequency')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'route')) {
                $table->string('route')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'quantity')) {
                $table->integer('quantity')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'refills')) {
                $table->integer('refills')->default(0);
            }

            if (!Schema::hasColumn('pharmacy_orders', 'pharmacy_name')) {
                $table->string('pharmacy_name')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'pharmacy_phone')) {
                $table->string('pharmacy_phone')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'pharmacy_fax')) {
                $table->string('pharmacy_fax')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'instructions')) {
                $table->text('instructions')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('pharmacy_orders', 'prescribed_at')) {
                $table->timestamp('prescribed_at')->nullable();
            }

            if (!Schema::hasColumn('pharmacy_orders', 'created_at') && !Schema::hasColumn('pharmacy_orders', 'updated_at')) {
                $table->timestamps();
            }
        });

        try {
            DB::statement('ALTER TABLE pharmacy_orders
                ADD CONSTRAINT pharmacy_orders_client_id_foreign
                FOREIGN KEY (client_id) REFERENCES clients(id)
                ON DELETE CASCADE');
        } catch (\Throwable $e) {
            // ignore if constraint already exists
        }

        try {
            DB::statement('ALTER TABLE pharmacy_orders
                ADD CONSTRAINT pharmacy_orders_provider_id_foreign
                FOREIGN KEY (provider_id) REFERENCES users(id)
                ON DELETE CASCADE');
        } catch (\Throwable $e) {
            // ignore if constraint already exists
        }
    }

    public function down(): void
    {
        // Safe rollback intentionally left empty
    }
};
