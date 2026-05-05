<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_note_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_note_id')->after('id');
            $table->string('type')->after('provider_note_id'); // icd or cpt
            $table->string('code')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('provider_note_codes', function (Blueprint $table) {
            $table->dropColumn(['provider_note_id', 'type', 'code']);
        });
    }
};
