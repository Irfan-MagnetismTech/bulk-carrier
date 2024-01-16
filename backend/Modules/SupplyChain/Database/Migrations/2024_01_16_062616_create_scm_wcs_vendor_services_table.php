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
        Schema::create('scm_wcs_vendor_services', function (Blueprint $table) {
            $table->id();            
            $table->bigInteger('scm_vendor_id')->nullable();
            $table->bigInteger('scm_wcs_id')->nullable();
            $table->bigInteger('scm_wr_id')->nullable();
            $table->bigInteger('scm_wcs_vendor_id')->nullable();
            $table->bigInteger('scm_service_id')->nullable();
            $table->bigInteger('scm_wcs_service_id')->nullable();
            $table->float('rate', 20, 2)->nullable();
            $table->float('quantity', 20, 2)->nullable();
            $table->string('quotation_ref_no')->nullable();
            $table->date('quotation_date')->nullable();            
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
        Schema::dropIfExists('scm_wcs_vendor_services');
    }
};
