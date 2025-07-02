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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable();
            $table->string('author')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('url')->nullable();
            $table->text('urlToImage')->nullable();
            $table->timestamp('publishedAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
