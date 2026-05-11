<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE IF NOT EXISTS events_event_id_seq START WITH 1');

        Schema::create('events', function (Blueprint $table) {
            $table->integer('event_id')
                  ->primary()
                  ->default(DB::raw("nextval('events_event_id_seq')"));

            $table->string('title');
            $table->text('body');
            $table->string('registration_url');
            $table->timestamp('event_time');
            $table->string('status');

            // extra columns
            $table->string('organiser')->default('Career Centre');
            $table->integer('spots')->default(200);
            $table->string('event_type')->default('Workshop');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        DB::statement('DROP SEQUENCE IF EXISTS events_event_id_seq');
    }
};