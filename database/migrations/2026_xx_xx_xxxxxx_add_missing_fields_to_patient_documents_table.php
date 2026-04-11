<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('patient_documents', 'facility_id')) {
                $table->unsignedBigInteger('facility_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('patient_documents', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->nullable()->after('facility_id');
            }

            if (!Schema::hasColumn('patient_documents', 'title')) {
                $table->string('title')->nullable()->after('patient_id');
            }

            if (!Schema::hasColumn('patient_documents', 'category')) {
                $table->string('category')->nullable()->after('title');
            }

            if (!Schema::hasColumn('patient_documents', 'file_path')) {
                $table->string('file_path')->nullable()->after('category');
            }

            if (!Schema::hasColumn('patient_documents', 'uploaded_by')) {
                $table->unsignedBigInteger('uploaded_by')->nullable()->after('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patient_documents', function (Blueprint $table) {
            $columns = [];

            foreach (['facility_id', 'patient_id', 'title', 'category', 'file_path', 'uploaded_by'] as $column) {
                if (Schema::hasColumn('patient_documents', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
