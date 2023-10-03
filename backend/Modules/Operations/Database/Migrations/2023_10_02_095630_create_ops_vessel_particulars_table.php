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
        Schema::create('ops_vessel_particulars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_vessel_id');
            // $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->text('attachment');
            $table->string('class_no');
            $table->string('loa');
            $table->string('depth');
            $table->string('ecdis_type');
            $table->string('maker_ssas');
            $table->string('engine_type');
            $table->string('bhp');
            $table->string('email');
            $table->string('lbc');
            $table->string('business_unit');
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
        Schema::dropIfExists('ops_vessel_particulars');
    }
};
