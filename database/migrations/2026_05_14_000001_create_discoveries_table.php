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
        Schema::create('discoveries', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('sector_x');
            $table->integer('sector_y');
            $table->integer('sector_z');
            $table->unsignedInteger('object_index');
            $table->double('x');
            $table->double('y');
            $table->double('z');
            $table->string('name');
            $table->string('discoverer_name');
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->unique(['type', 'sector_x', 'sector_y', 'sector_z', 'object_index'], 'discoveries_object_identity_unique');
            $table->index(['sector_x', 'sector_y', 'sector_z'], 'discoveries_sector_lookup_index');
            $table->index(['type', 'name'], 'discoveries_type_name_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discoveries');
    }
};
