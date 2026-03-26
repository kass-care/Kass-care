<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // alerts table already exists
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
