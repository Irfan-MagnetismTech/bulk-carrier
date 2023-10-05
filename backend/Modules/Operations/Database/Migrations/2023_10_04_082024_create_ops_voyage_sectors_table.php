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
        Schema::create('ops_voyage_sectors', function (Blueprint $table) {
            $table->id();       
            $table->foreignId('ops_voyage_id')->constrained();
            $table->foreignId('ops_cargo_tariff_id')->constrained();
            $table->string('loading_point');
            $table->string('unloading_point');
            $table->string('rate');
            $table->float('initial_survey_qty');
            $table->float('approx_amount');
            $table->float('discount');
            $table->float('discount_amount');
            $table->float('approx_amount_after_disscount');
            $table->float('final_survey_qty');
            $table->float('final_received_qty');
            $table->float('boat_note_qty');
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
        Schema::dropIfExists('ops_voyage_sectors');
    }
};
