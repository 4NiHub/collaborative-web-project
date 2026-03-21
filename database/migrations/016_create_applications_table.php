<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS applications_application_id_seq START WITH 1');

        Schema::create('applications', function (Blueprint $table) {
            $table->integer('application_id')
                  ->primary()
                  ->default(DB::raw("nextval('applications_application_id_seq')"));

            $table->integer('student_id');
            $table->integer('job_id');
            $table->string('resume_url');
            $table->timestamp('creation_time')->useCurrent();
            $table->string('status');

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('job_id')->references('job_id')->on('jobs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
        DB::statement('DROP SEQUENCE IF EXISTS applications_application_id_seq');
    }
};