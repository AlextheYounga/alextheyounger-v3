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
        if (! Schema::hasTable('repositories')) {
            return;
        }

        $columns = array_filter(['visibility', 'host', 'url'], fn (string $column): bool => Schema::hasColumn('repositories', $column));

        if ($columns === []) {
            return;
        }

        Schema::table('repositories', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('repositories')) {
            return;
        }

        Schema::table('repositories', function (Blueprint $table): void {
            if (! Schema::hasColumn('repositories', 'visibility')) {
                $table->string('visibility')->nullable();
            }

            if (! Schema::hasColumn('repositories', 'host')) {
                $table->string('host')->nullable();
            }

            if (! Schema::hasColumn('repositories', 'url')) {
                $table->string('url')->nullable();
            }
        });
    }
};
