<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_name')->nullable();
            $table->text('external_link')->nullable();
            $table->string('external_image_link')->nullable();
            $table->string('framework')->nullable();
            $table->string('scope')->nullable();
            $table->text('excerpt')->nullable();
            $table->integer('position')->nullable();
            $table->json('properties')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
