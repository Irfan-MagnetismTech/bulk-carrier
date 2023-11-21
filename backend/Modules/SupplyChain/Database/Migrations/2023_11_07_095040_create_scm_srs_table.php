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
        Schema::create('scm_srs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses');
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->date('date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('business_unit')->comment('TSLL,PSML')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
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
        Schema::dropIfExists('scm_srs');
    }
};