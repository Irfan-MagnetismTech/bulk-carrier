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
        Schema::create('scm_vendor_bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_vendor_id')->nullable();
            $table->bigInteger('scm_warehouse_id')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('bill_no')->nullable();
            $table->date('date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('attachment')->nullable();
            $table->decimal('sub_total', 20, 2)->nullable();        
            $table->decimal('discount', 20, 2)->nullable();
            $table->decimal('net_amount', 20, 2)->nullable();
            $table->string('business_unit')->comment('psml,tsll')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
            $table->tinyInteger('is_paid')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('usd_to_bdt', 20, 2)->nullable();
            $table->decimal('currency_to_usd', 20, 2)->nullable();
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
        Schema::dropIfExists('scm_vendor_bills');
    }
};
