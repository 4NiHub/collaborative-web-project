<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS subjects_groups_bridge_table_subject_group_id_seq START WITH 1');

        Schema::create('subjects_groups_bridge_table', function (Blueprint $table) {
            $table->integer('subject_group_id')
                  ->primary()
                  ->default(DB::raw("nextval('subjects_groups_bridge_table_subject_group_id_seq')"));

            $table->integer('subject_id');
            $table->integer('group_id');

            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->foreign('group_id')->references('group_id')->on('groups')->onDelete('cascade');

            $table->unique(['subject_id', 'group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects_groups_bridge_table');
        DB::statement('DROP SEQUENCE IF EXISTS subjects_groups_bridge_table_subject_group_id_seq');
    }
};