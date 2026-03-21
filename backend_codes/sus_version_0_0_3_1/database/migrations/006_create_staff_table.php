<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS staff_staff_id_seq START WITH 1');

        Schema::create('staff', function (Blueprint $table) {
            $table->integer('staff_id')
                  ->primary()
                  ->default(DB::raw("nextval('staff_staff_id_seq')"));

            $table->integer('user_id')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('job_position')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
        DB::statement('DROP SEQUENCE IF EXISTS staff_staff_id_seq');
    }
};