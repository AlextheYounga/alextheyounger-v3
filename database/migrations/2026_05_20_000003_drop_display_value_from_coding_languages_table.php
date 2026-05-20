<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('coding_languages') || ! Schema::hasColumn('coding_languages', 'display_value')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->dropColumn('display_value');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('coding_languages') || Schema::hasColumn('coding_languages', 'display_value')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->float('display_value')->nullable();
        });
    }
};
