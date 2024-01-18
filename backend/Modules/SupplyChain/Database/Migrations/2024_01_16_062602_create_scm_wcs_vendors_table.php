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
        Schema::create('scm_wcs_vendors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_vendor_id')->nullable();
            $table->bigInteger('scm_wcs_id')->nullable();
            $table->string('quotation_ref_no')->nullable();
            $table->date('quotation_date')->nullable();
            $table->string('attachment')->nullable();
            $table->date('quotation_validity')->nullable();
            $table->enum('payment_mode', ['Cash','Credit','Advance','Bank'])->nullable();
            $table->string('credit_term')->nullable();
            $table->string('vat')->nullable();
            $table->string('ait')->nullable();
            $table->float('security_money', 20, 2)->nullable();
            $table->string('adjustment_policy')->nullable();
            $table->bigInteger('is_selected')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable();
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
        Schema::dropIfExists('scm_wcs_vendors');
    }
};
