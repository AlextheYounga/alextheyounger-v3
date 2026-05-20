<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('coding_languages')) {
            return;
        }

        if (Schema::hasColumn('coding_languages', 'language') && ! Schema::hasColumn('coding_languages', 'name')) {
            Schema::table('coding_languages', function (Blueprint $table): void {
                $table->renameColumn('language', 'name');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('coding_languages')) {
            return;
        }

        if (Schema::hasColumn('coding_languages', 'name') && ! Schema::hasColumn('coding_languages', 'language')) {
            Schema::table('coding_languages', function (Blueprint $table): void {
                $table->renameColumn('name', 'language');
            });
        }
    }
};
