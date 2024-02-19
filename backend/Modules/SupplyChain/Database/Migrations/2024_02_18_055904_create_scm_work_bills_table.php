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
        Schema::create('scm_work_bills', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->bigInteger('scm_wo_id')->nullable();
            // $table->bigInteger('scm_wr_id')->nullable();
            $table->bigInteger('scm_warehouse_id')->nullable();
            $table->bigInteger('scm_vendor_id')->nullable();
            $table->string('currency')->nullable();
            $table->float('conversion_rate', 20, 2)->nullable();
            $table->float('bill_amount', 20, 2)->nullable();
            $table->float('security_amount', 20, 2)->nullable();
            $table->float('adjustment_amount', 20, 2)->nullable();
            $table->float('net_amount', 20, 2)->nullable();            
            $table->string('purchase_center')->nullable();
            $table->enum('status', ['Settled', 'Remaining'])->default('Remaining');
            $table->bigInteger('created_by')->comment('user_id')->nullable();
            $table->string('attachment')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('scm_work_bills');
    }
};
