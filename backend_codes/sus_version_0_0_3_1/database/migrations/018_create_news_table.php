<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS news_news_id_seq START WITH 1');

        Schema::create('news', function (Blueprint $table) {
            $table->integer('news_id')
                  ->primary()
                  ->default(DB::raw("nextval('news_news_id_seq')"));

            $table->string('title');
            $table->text('body');
            $table->timestamp('creation_time')->useCurrent();

            // extra columns from your ALTER
            $table->string('category')->default('Academic')->nullable();
            $table->string('excerpt')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
        DB::statement('DROP SEQUENCE IF EXISTS news_news_id_seq');
    }
};