<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS groups_group_id_seq START WITH 1');

        Schema::create('groups', function (Blueprint $table) {
            $table->integer('group_id')
                  ->primary()
                  ->default(DB::raw("nextval('groups_group_id_seq')"));

            $table->string('group_name');
            $table->integer('group_level');
            $table->boolean('is_active')->default(true);
            $table->date('creation_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
        DB::statement('DROP SEQUENCE IF EXISTS groups_group_id_seq');
    }
};