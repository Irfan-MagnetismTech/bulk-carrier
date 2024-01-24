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
        Schema::create('scm_cs_payment_infos', function (Blueprint $table) {
            $table->id();
            $table->string('scm_cs_id')->nullable();
            $table->string('scm_cs_vendor_id')->nullable();
            $table->string('scm_vendor_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('name_of_bank')->nullable();
            $table->string('cfr_value')->nullable();
            $table->string('lc_margin')->nullable();
            $table->string('bank_commission')->nullable();
            $table->string('vat')->nullable();
            $table->string('others')->nullable();
            $table->string('insurence_premium')->nullable();
            $table->string('document_value')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('insurence_company')->nullable();
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
        Schema::dropIfExists('scm_cs_payment_infos');
    }
};
