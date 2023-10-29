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
        Schema::create('scm_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('cost_center_id')->nullable();
            $table->string('address')->nullable();
            $table->string('short_code')->unique()->nullable();
            $table->bigInteger('ops_vessel_id')->nullable();
            $table->string('business_unit')->nullable();
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
        Schema::dropIfExists('scm_warehouses');
    }
};
