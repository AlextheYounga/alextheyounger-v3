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
        if (! Schema::hasTable('coding_languages')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            if (Schema::hasColumn('coding_languages', 'language') && ! Schema::hasColumn('coding_languages', 'name')) {
                $table->renameColumn('language', 'name');
            }

            if (Schema::hasColumn('coding_languages', 'width') && ! Schema::hasColumn('coding_languages', 'percentage')) {
                $table->renameColumn('width', 'percentage');
            }
        });

        Schema::table('coding_languages', function (Blueprint $table): void {
            if (Schema::hasColumn('coding_languages', 'display_value')) {
                $table->dropColumn('display_value');
            }

            if (Schema::hasColumn('coding_languages', 'color')) {
                $table->dropColumn('color');
            }

            if (Schema::hasColumn('coding_languages', 'project_count')) {
                $table->dropColumn('project_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('coding_languages')) {
            return;
        }

        Schema::table('coding_languages', function (Blueprint $table): void {
            if (Schema::hasColumn('coding_languages', 'percentage') && ! Schema::hasColumn('coding_languages', 'width')) {
                $table->renameColumn('percentage', 'width');
            }

            if (Schema::hasColumn('coding_languages', 'name') && ! Schema::hasColumn('coding_languages', 'language')) {
                $table->renameColumn('name', 'language');
            }
        });

        Schema::table('coding_languages', function (Blueprint $table): void {
            if (! Schema::hasColumn('coding_languages', 'display_value')) {
                $table->float('display_value')->nullable();
            }

            if (! Schema::hasColumn('coding_languages', 'color')) {
                $table->string('color')->nullable();
            }

            if (! Schema::hasColumn('coding_languages', 'project_count')) {
                $table->integer('project_count')->nullable();
            }
        });
    }
};
