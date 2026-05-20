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
        Schema::dropIfExists('page_content');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('page_content', function (Blueprint $table) {
            $table->id();
            $table->string('html_id')->nullable();
            $table->string('name');
            $table->string('key');
            $table->string('view');
            $table->mediumText('content');
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }
};
