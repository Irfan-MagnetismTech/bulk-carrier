<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ops_voyage_boat_note_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_voyage_boat_note_id')->constrained('ops_voyage_boat_notes');
            $table->enum('voyage_note_type', ['Boat Note', 'Draft Survey','Receipt Copy','Final Survey']);
            $table->date('date');
            $table->dateTime('discharge_date');
            $table->text('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ops_voyage_boat_note_lines');
    }
};
