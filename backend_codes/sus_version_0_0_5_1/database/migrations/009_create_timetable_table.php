<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS timetable_session_id_seq START WITH 1');

        Schema::create('timetable', function (Blueprint $table) {
            $table->integer('session_id')
                  ->primary()
                  ->default(DB::raw("nextval('timetable_session_id_seq')"));

            $table->integer('subject_group_id');
            $table->integer('time_slot');
            $table->integer('day_slot');
            $table->integer('room_number');

            // extra columns
            $table->string('session_type')->default('Lecture');
            $table->string('building')->default('Block A');

            $table->foreign('subject_group_id')->references('subject_group_id')->on('subjects_groups_bridge_table');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable');
        DB::statement('DROP SEQUENCE IF EXISTS timetable_session_id_seq');
    }
};