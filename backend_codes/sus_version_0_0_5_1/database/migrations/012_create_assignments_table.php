<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS assignments_assignment_id_seq START WITH 1');

        Schema::create('assignments', function (Blueprint $table) {
            $table->integer('assignment_id')
                  ->primary()
                  ->default(DB::raw("nextval('assignments_assignment_id_seq')"));

            $table->string('title');
            $table->integer('subject_id');
            $table->text('body')->nullable();
            $table->integer('weight');
            $table->timestamp('due_time')->nullable();
            $table->string('file_url')->nullable();

            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
        DB::statement('DROP SEQUENCE IF EXISTS assignments_assignment_id_seq');
    }
};