<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
			$table->string('hash')->unique();
			$table->string('name')->unique();
			$table->mediumText('bio')->nullable();
			$table->json('contacts')->nullable();
			$table->json('references')->nullable();
			$table->json('experience')->nullable();
			$table->json('education')->nullable();
			$table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
