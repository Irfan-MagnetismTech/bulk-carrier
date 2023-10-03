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
        Schema::create('ops_cargo_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('tariff_name');
            $table->bigInteger('ops_vessel_id');
            $table->string('loading_point');
            $table->string('unloading_point');
            $table->bigInteger('ops_cargo_type_id');
            $table->string('currency');
            $table->tinyInteger('status');
            $table->string('business_unit');
            
            $table->timestamps();
            
            $table->foreign('ops_cargo_type_id')->references('id')->on('ops_cargo_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ops_cargo_tariffs');
    }
};
