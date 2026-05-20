<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('coding_languages') || ! Schema::hasColumn('coding_languages', 'color')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->dropColumn('color');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('coding_languages') || Schema::hasColumn('coding_languages', 'color')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->string('color')->nullable();
        });
    }
};
