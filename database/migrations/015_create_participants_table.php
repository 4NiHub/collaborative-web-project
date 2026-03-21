<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS participants_participant_id_seq START WITH 1');

        Schema::create('participants', function (Blueprint $table) {
            $table->integer('participant_id')
                  ->primary()
                  ->default(DB::raw("nextval('participants_participant_id_seq')"));

            $table->integer('student_id');
            $table->integer('event_id');
            $table->timestamp('registration_time')->useCurrent();

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
        DB::statement('DROP SEQUENCE IF EXISTS participants_participant_id_seq');
    }
};