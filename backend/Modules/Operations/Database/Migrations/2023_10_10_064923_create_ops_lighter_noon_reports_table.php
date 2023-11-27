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
        Schema::create('ops_lighter_noon_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_vessel_id');
            $table->foreignId('ops_voyage_id')->nullable();
            $table->string('ship_master')->nullable();
            $table->string('chief_engineer');
            $table->string('noon_position')->nullable();
            $table->string('status')->nullable();
            $table->string('engine_running_hours')->nullable();
            $table->string('lat_long')->nullable();
            $table->dateTime('date');
            $table->string('last_port')->nullable();
            $table->string('next_port')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL', 'BOTH'])->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('ops_lighter_noon_reports');
    }
};
