<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS subjects_subject_id_seq START WITH 1');

        Schema::create('subjects', function (Blueprint $table) {
            $table->integer('subject_id')
                  ->primary()
                  ->default(DB::raw("nextval('subjects_subject_id_seq')"));

            $table->string('name');
            $table->integer('mentor_id')->nullable();
            $table->integer('credits')->default(15);
            $table->text('description')->nullable();

            $table->foreign('mentor_id')->references('mentor_id')->on('mentors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
        DB::statement('DROP SEQUENCE IF EXISTS subjects_subject_id_seq');
    }
};