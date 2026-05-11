<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS roles_role_id_seq START WITH 1');

        Schema::create('roles', function (Blueprint $table) {
            $table->integer('role_id')
                  ->primary()
                  ->default(DB::raw("nextval('roles_role_id_seq')"));

            $table->string('role_name')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
        DB::statement('DROP SEQUENCE IF EXISTS roles_role_id_seq');
    }
};