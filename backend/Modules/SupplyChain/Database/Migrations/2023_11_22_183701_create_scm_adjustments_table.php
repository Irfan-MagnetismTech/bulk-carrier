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
        Schema::create('scm_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->unsignedBigInteger('scm_warehouse_id')->nullable();
            $table->unsignedBigInteger('acc_cost_center_id')->nullable();
            $table->date('date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('business_unit')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('scm_adjustments');
    }
};
