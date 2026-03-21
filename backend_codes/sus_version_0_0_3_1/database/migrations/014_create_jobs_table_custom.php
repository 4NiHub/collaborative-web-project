<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS jobs_job_id_seq START WITH 1');

        Schema::create('jobs', function (Blueprint $table) {
            $table->integer('job_id')
                  ->primary()
                  ->default(DB::raw("nextval('jobs_job_id_seq')"));

            $table->string('title');
            $table->string('company')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable();
            $table->string('salary')->nullable();
            $table->string('deadline')->nullable();
            $table->timestamp('creation_time')->useCurrent();
            $table->string('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
        DB::statement('DROP SEQUENCE IF EXISTS jobs_job_id_seq');
    }
};