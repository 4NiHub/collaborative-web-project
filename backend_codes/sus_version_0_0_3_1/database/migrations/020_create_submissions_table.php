<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS submissions_submission_id_seq START WITH 1');

        Schema::create('submissions', function (Blueprint $table) {
            $table->integer('submission_id')
                  ->primary()
                  ->default(DB::raw("nextval('submissions_submission_id_seq')"));

            $table->integer('assignment_id');
            $table->integer('student_id');
            $table->string('file_url');
            $table->timestamp('submission_time')->useCurrent();
            $table->integer('grade')->nullable();
            $table->timestamp('grade_time')->nullable();

            $table->foreign('assignment_id')->references('assignment_id')->on('assignments')->onDelete('cascade');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        DB::statement('DROP SEQUENCE IF EXISTS submissions_submission_id_seq');
    }
};