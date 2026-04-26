<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_id')->nullable()->constrained('facilities')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();

            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('subject')->nullable();
            $table->text('message');
            $table->text('provider_reply')->nullable();

            $table->string('priority')->default('normal');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_messages');
    }
};
