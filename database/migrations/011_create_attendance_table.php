<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS attendance_attendee_id_seq START WITH 1');

        Schema::create('attendance', function (Blueprint $table) {
            $table->integer('attendee_id')
                  ->primary()
                  ->default(DB::raw("nextval('attendance_attendee_id_seq')"));

            $table->integer('student_id');
            $table->integer('session_id');
            $table->timestamp('session_date');
            $table->boolean('is_present');

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('session_id')->references('session_id')->on('timetable')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
        DB::statement('DROP SEQUENCE IF EXISTS attendance_attendee_id_seq');
    }
};