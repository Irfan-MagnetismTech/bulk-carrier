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
        Schema::create('scm_wos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_wcs_id')->nullable();
            $table->string('ref_no');
            $table->date('date')->nullable();
            $table->bigInteger('scm_warehouse_id')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->bigInteger('scm_vendor_id')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('usd_to_bdt')->nullable();
            $table->decimal('foreign_to_usd')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->decimal('net_amount')->nullable();
            $table->bigInteger('purchase_center')->nullable();
            $table->string('remarks')->nullable();
            $table->tinyInteger('is_closed')->default(0)->comment('0, 1');
            $table->integer('closed_by')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->string('closing_remarks')->nullable();
            $table->string('confirmation_status')->nullable();
            $table->enum('status', ['Pending', 'WIP', 'Closed'])->default('Pending');
            $table->bigInteger('created_by')->comment('user_id')->nullable();
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
        Schema::dropIfExists('wos');
    }
};
