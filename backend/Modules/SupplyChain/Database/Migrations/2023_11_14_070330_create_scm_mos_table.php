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
        Schema::create('scm_mos', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->foreignId('scm_mmr_id')->constrained('scm_mmrs');
            $table->foreignId('from_warehouse_id')->constrained('scm_warehouses');
            $table->foreignId('to_warehouse_id')->constrained('scm_warehouses');
            $table->date('date')->nullable();
            $table->string('business_unit')->comment('psml,tsll')->nullable();
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
        Schema::dropIfExists('scm_mos');
    }
};
