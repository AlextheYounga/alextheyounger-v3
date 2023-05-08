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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('client')->nullable();
            $table->string('title')->nullable();
            $table->text('intro')->nullable();
            $table->text('body')->nullable();
            $table->text('footer')->nullable();
            $table->string('projects')->nullable();
            $table->string('path')->nullable();
            $table->string('client_key')->nullable();
            $table->string('client_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
