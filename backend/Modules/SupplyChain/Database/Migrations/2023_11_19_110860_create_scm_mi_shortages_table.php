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
        Schema::create('scm_mi_shortages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scm_mi_id')->nullable();
            $table->foreign('scm_mi_id')->references('id')->on('scm_mis')->onDelete('cascade');
            $table->string('shortage_type')->nullable();
            $table->unsignedBigInteger('scm_warehouse_id')->nullable();
            $table->unsignedBigInteger('acc_cost_center_id')->nullable();
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
        Schema::dropIfExists('scm_mi_shortages');
    }
};
