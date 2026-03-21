<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS grades_grade_id_seq START WITH 1');

        Schema::create('grades', function (Blueprint $table) {
            $table->integer('grade_id')
                  ->primary()
                  ->default(DB::raw("nextval('grades_grade_id_seq')"));

            $table->integer('student_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->string('grade')->nullable();
            $table->decimal('points', 3, 2)->nullable();
            $table->integer('percentage')->nullable();
            $table->integer('attendance')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('student_id')->references('student_id')->on('students')->nullOnDelete();
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
        DB::statement('DROP SEQUENCE IF EXISTS grades_grade_id_seq');
    }
};