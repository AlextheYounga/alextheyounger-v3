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
        Schema::dropIfExists('proposals');

        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->string('client')->nullable();
            $table->string('title')->nullable();
            $table->json('content')->nullable();
            $table->json('line_items')->nullable();
            $table->float('total')->default(0.0);
            $table->boolean('client_agreement')->default(false);
            $table->string('client_signature')->nullable();
            $table->datetime('client_sign_date')->nullable();
            $table->json('properties')->nullable();
            $table->date('completion_date')->nullable();
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
