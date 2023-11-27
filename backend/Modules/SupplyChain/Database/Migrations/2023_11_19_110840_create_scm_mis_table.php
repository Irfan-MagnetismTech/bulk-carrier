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
        Schema::create('scm_mis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scm_mmr_id')->nullable();
            $table->foreign('scm_mmr_id')->references('id')->on('scm_mmrs')->onDelete('cascade');
            $table->unsignedBigInteger('scm_mo_id')->nullable();
            // $table->foreign('scm_mo_id')->references('id')->on('scm_mos')->onDelete('cascade');
            $table->string('ref_no')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('from_warehouse_id')->constrained('scm_warehouses');
            $table->foreignId('to_warehouse_id')->constrained('scm_warehouses');
            // $table->unsignedBigInteger('scm_warehouse_id')->nullable();
            // // $table->foreign('scm_warehouse_id')->references('id')->on('scm_warehouses')->onDelete('cascade');
            // $table->unsignedBigInteger('scm_cost_center_id')->nullable();
            // $table->foreign('scm_cost_center_id')->references('id')->on('scm_cost_centers')->onDelete('cascade');
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
        Schema::dropIfExists('scm_mis');
    }
};
