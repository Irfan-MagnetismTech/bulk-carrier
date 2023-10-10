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
        Schema::disableForeignKeyConstraints();
        Schema::create('ops_lighter_noon_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->bigInteger('ops_voyage_id');
            $table->foreign('ops_voyage_id')->references('id')->on('ops_voyages');
            $table->string('ship_master')->nullable();
            $table->string('chief_engineer');
            $table->string('noon_position')->nullable();
            $table->string('status')->nullable();
            $table->string('engine_running_hours')->nullable();
            $table->string('lat_long');->nullable();
            $table->dateTime('date');
            $table->string('last_port')->nullable();
            $table->string('next_port')->nullable();
            $table->string('business_unit')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ops_lighter_noon_reports');
    }
};
