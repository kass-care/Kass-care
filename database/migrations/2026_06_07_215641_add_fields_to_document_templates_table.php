<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('document_templates', 'title')) {
                $table->string('title')->nullable();
            }

            if (!Schema::hasColumn('document_templates', 'state')) {
                $table->string('state')->nullable();
            }

            if (!Schema::hasColumn('document_templates', 'category')) {
                $table->string('category')->nullable();
            }

            if (!Schema::hasColumn('document_templates', 'content')) {
                $table->longText('content')->nullable();
            }

            if (!Schema::hasColumn('document_templates', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            if (Schema::hasColumn('document_templates', 'active')) {
                $table->dropColumn('active');
            }

            if (Schema::hasColumn('document_templates', 'content')) {
                $table->dropColumn('content');
            }

            if (Schema::hasColumn('document_templates', 'category')) {
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('document_templates', 'state')) {
                $table->dropColumn('state');
            }

            if (Schema::hasColumn('document_templates', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
