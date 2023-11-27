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
        Schema::create('ops_bulk_noon_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->unsignedBigInteger('ops_voyage_id');
            $table->foreign('ops_voyage_id')->references('id')->on('ops_voyages');
            $table->string('ship_master')->nullable();
            $table->string('chief_engineer')->nullable();
            $table->string('wind_condition')->nullable();
            $table->string('type')->nullable();
            $table->string('date_time')->nullable();
            $table->string('gmt_time')->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('fuel_figures_from')->nullable();
            $table->string('fw_last_day_noon_rob')->nullable();
            $table->string('fw_production')->nullable();
            $table->string('fw_consumption')->nullable();
            $table->string('fw_today_noon_rob')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']);  
            $table->string('sea_condition')->nullable()->nullable();
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
        Schema::dropIfExists('ops_bulk_noon_reports');
    }
};
