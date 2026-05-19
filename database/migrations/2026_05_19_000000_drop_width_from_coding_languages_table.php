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
        if (! Schema::hasTable('coding_languages') || ! Schema::hasColumn('coding_languages', 'width')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->dropColumn('width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('coding_languages') || Schema::hasColumn('coding_languages', 'width')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->float('width')->nullable();
        });
    }
};
