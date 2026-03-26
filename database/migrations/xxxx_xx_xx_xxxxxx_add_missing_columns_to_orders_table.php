<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orders', 'facility_id')) {
                $table->unsignedBigInteger('facility_id')->nullable()->after('client_id');
            }

            if (!Schema::hasColumn('orders', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('facility_id');
            }

            if (!Schema::hasColumn('orders', 'type')) {
                $table->string('type')->nullable()->after('created_by');
            }

            if (!Schema::hasColumn('orders', 'title')) {
                $table->string('title')->nullable()->after('type');
            }

            if (!Schema::hasColumn('orders', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (!Schema::hasColumn('orders', 'destination')) {
                $table->string('destination')->nullable()->after('description');
            }

            if (!Schema::hasColumn('orders', 'priority')) {
                $table->string('priority')->default('routine')->after('destination');
            }

            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('priority');
            }

            if (!Schema::hasColumn('orders', 'ordered_date')) {
                $table->date('ordered_date')->nullable()->after('status');
            }

            if (!Schema::hasColumn('orders', 'follow_up_date')) {
                $table->date('follow_up_date')->nullable()->after('ordered_date');
            }

            if (!Schema::hasColumn('orders', 'result_notes')) {
                $table->text('result_notes')->nullable()->after('follow_up_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'client_id',
                'facility_id',
                'created_by',
                'type',
                'title',
                'description',
                'destination',
                'priority',
                'status',
                'ordered_date',
                'follow_up_date',
                'result_notes',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
