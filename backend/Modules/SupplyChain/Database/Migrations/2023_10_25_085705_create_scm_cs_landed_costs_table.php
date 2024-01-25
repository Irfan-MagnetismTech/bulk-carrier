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
        Schema::create('scm_cs_landed_costs', function (Blueprint $table) {
            $table->id();
            $table->string('scm_cs_id')->nullable();
            $table->string('scm_cs_vendor_id')->nullable();
            $table->string('scm_vendor_id')->nullable();
            $table->string('hs_codes')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('product_price')->nullable();
            $table->string('freight_charge')->nullable();
            $table->string('cfr_value')->nullable();
            $table->integer('insurance')->nullable();
            $table->double('assesable_value_b')->nullable();
            $table->integer('landing_charge')->nullable();
            $table->double('assesable_value_a')->nullable();
            $table->double('cd')->nullable();
            $table->double('rd')->nullable();
            $table->double('sd')->nullable();
            $table->double('vat')->nullable();
            $table->double('ait')->nullable();
            $table->double('at')->nullable();
            $table->double('total_duty')->nullable();
            $table->double('others')->nullable();
            $table->double('total_landed_cost')->nullable();
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
        Schema::dropIfExists('scm_cs_landed_costs');
    }
};
