<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS notifications_notification_id_seq START WITH 1');

        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('notification_id')
                  ->primary()
                  ->default(DB::raw("nextval('notifications_notification_id_seq')"));

            $table->integer('user_id');
            $table->string('title');
            $table->text('body');
            $table->timestamp('creation_time')->useCurrent();
            $table->boolean('is_active')->default(true);

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        DB::statement('DROP SEQUENCE IF EXISTS notifications_notification_id_seq');
    }
};