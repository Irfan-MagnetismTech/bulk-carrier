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
        Schema::create('scm_pos', function (Blueprint $table) {
            $table->comment('po => Purchase order');
            $table->id();
            $table->foreignId('scm_pr_id')->constrained('scm_pr');
            $table->foreignId('scm_cs_id')->constrained('scm_cs');
            $table->date('date')->nullable();
            $table->foreignId('scm_vendor_id')->constrained('scm_vendors')->nullable();
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('foreign_to_bdt')->comment('material,supplier')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('vat')->nullable();
            $table->string('business_unit')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->decimal('net_amount')->nullable();
            $table->decimal('foreign_to_usd')->nullable();
            $table->date('pr_date')->nullable();
            $table->bigInteger('purchase_center')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('scm_pos');
    }
};
