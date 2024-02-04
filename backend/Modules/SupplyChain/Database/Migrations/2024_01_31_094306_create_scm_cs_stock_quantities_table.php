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
        Schema::create('scm_cs_stock_quantities', function (Blueprint $table) {
            $table->id();
            $table->integer('scm_cs_id')->unsigned()->nullable();
            $table->integer('scm_material_id')->unsigned()->nullable();
            $table->integer('at_port')->default(0)->nullable();
            $table->integer('in_transit')->default(0)->nullable();
            $table->integer('under_lc')->default(0)->nullable();
            $table->integer('total_stock')->default(0)->nullable();
            $table->integer('days_to_run')->default(0)->nullable();
            $table->integer('available_in_other_unit')->default(0)->nullable();
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
        Schema::dropIfExists('scm_cs_stock_quantities');
    }
};
