<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('smart_phrases', function (Blueprint $table) {
            $table->string('category')->nullable()->after('shortcut');
        });
    }

    public function down(): void
    {
        Schema::table('smart_phrases', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
