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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('image_name')->nullable();
            $table->text('external_link')->nullable();
            $table->string('external_image_link')->nullable();
            $table->string('subtitle')->nullable();
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
        Schema::dropIfExists('books');
    }
};
