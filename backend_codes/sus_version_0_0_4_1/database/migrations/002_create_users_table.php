<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS users_user_id_seq START WITH 1');

        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id')
                  ->primary()
                  ->default(DB::raw("nextval('users_user_id_seq')"));

            $table->integer('role_id');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->rememberToken();
            $table->timestamp('creation_time')->useCurrent();

            $table->foreign('role_id')->references('role_id')->on('roles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        DB::statement('DROP SEQUENCE IF EXISTS users_user_id_seq');
    }
};