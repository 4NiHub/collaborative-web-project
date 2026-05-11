<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS mentors_mentor_id_seq START WITH 1');

        Schema::create('mentors', function (Blueprint $table) {
            $table->integer('mentor_id')
                  ->primary()
                  ->default(DB::raw("nextval('mentors_mentor_id_seq')"));

            $table->integer('user_id')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('phone_number');

            // extra columns from ALTER
            $table->string('department')->nullable()->default('Computer Science');
            $table->string('office_location')->nullable()->default('Block A, Room 205');
            $table->string('office_hours')->nullable()->default('Mon 14:00–16:00, Wed 10:00–12:00');
            $table->text('bio')->nullable()->default('Expert in computer science with 10+ years of teaching experience.');
            $table->string('nationality')->nullable()->default('British');
            $table->text('languages')->nullable()->default('English');
            $table->jsonb('profile_data')->nullable()->default('{}');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentors');
        DB::statement('DROP SEQUENCE IF EXISTS mentors_mentor_id_seq');
    }
};