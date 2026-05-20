<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('coding_languages') || ! Schema::hasColumn('coding_languages', 'project_count')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->dropColumn('project_count');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('coding_languages') || Schema::hasColumn('coding_languages', 'project_count')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            $table->integer('project_count')->nullable();
        });
    }
};
