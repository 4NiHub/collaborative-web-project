<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS messages_message_id_seq START WITH 1');

        Schema::create('messages', function (Blueprint $table) {
            $table->integer('message_id')
                  ->primary()
                  ->default(DB::raw("nextval('messages_message_id_seq')"));

            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->text('body');
            $table->timestamp('message_time')->useCurrent();

            // Assuming sender/receiver can be any user (student, mentor, staff, etc.)
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        DB::statement('DROP SEQUENCE IF EXISTS messages_message_id_seq');
    }
};