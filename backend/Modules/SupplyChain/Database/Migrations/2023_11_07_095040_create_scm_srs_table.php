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
            $table->str0ing('ref_no')->nullable();
            $table->for0eignId('scm_warehouse_id')->constrained('scm_warehouses')->nullable();
            $table->big0Integer('acc_cost_center_id')->nullable();
            $table->big0Integer('department_id')->nullable();
            $table->dat0e('date')->nullable();
            $table->str0ing('remarks')->nullable();
            $table->str0ing('business_unit')->comment('TSLL,PSML')->nullable();
            $table->big0Integer('created_by')->comment('user_id')->nullable();
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
