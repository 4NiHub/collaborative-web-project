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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');                     // ← use unsignedBigInteger
            $table->unsignedBigInteger('news_id');                     // same for news

            $table->timestamps();

            // Explicit foreign keys – point to correct columns
            $table->foreign('user_id')
                ->references('user_id')                              // ← not "id" !
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('news_id')
                ->references('news_id')
                ->on('news')
                ->onDelete('cascade');

            $table->unique(['user_id', 'news_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
