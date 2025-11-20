<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('languages', 'coding_languages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('coding_languages', 'languages');
    }
};
