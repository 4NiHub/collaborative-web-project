<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS students_student_id_seq START WITH 1');

        Schema::create('students', function (Blueprint $table) {
            $table->integer('student_id')
                  ->primary()
                  ->default(DB::raw("nextval('students_student_id_seq')"));

            $table->integer('user_id')->unique();
            $table->string('name');
            $table->string('surname');
            $table->integer('entry_year');
            $table->integer('group_id');
            $table->string('phone_number');
            $table->decimal('gpa', 3, 2)->default(0.0);
            $table->integer('credits_completed')->default(0);
            $table->integer('attendance_percentage')->default(0);

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('group_id')->on('groups');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
        DB::statement('DROP SEQUENCE IF EXISTS students_student_id_seq');
    }
};