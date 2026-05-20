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

        if (Schema::hasColumn('coding_languages', 'width') && ! Schema::hasColumn('coding_languages', 'percentage')) {
            Schema::table('coding_languages', function (Blueprint $table): void {
                $table->renameColumn('width', 'percentage');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('coding_languages')) {
            return;
        }

        if (Schema::hasColumn('coding_languages', 'percentage') && ! Schema::hasColumn('coding_languages', 'width')) {
            Schema::table('coding_languages', function (Blueprint $table): void {
                $table->renameColumn('percentage', 'width');
            });
        }
    }
};
