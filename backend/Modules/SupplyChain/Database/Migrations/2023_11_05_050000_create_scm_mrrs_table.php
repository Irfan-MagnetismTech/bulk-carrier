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
        Schema::create('scm_mrrs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('cash,local,foreign')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('scm_po_id')->constrained('scm_pos')->nullable();
            $table->foreignId('scm_pr_id')->constrained('scm_prs')->nullable();
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses')->nullable();
            $table->foreignId('scm_lc_record_id')->constrained('scm_lc_records')->nullable();
            $table->bigInteger('scm_cs_id')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->text('remarks')->nullable();
            $table->string('challan_no')->nullable();
            $table->bigInteger('is_qc_passed')->nullable();
            $table->text('qc_remarks')->nullable();
            $table->string('business_unit')->comment('TSLL,PSML')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
            $table->tinyInteger('is_completed')->comment('0, 1')->nullable();
            $table->bigInteger('iou_id')->nullable();
            $table->bigInteger('purchase_center')->nullable();
            $table->string('iou_no')->nullable();
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
        Schema::dropIfExists('scm_mrrs');
    }
};
