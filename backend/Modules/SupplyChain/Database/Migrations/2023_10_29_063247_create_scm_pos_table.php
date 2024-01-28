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
            $table->string('ref_no')->nullable();
            $table->unsignedBigInteger('scm_cs_id')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('scm_vendor_id')->constrained('scm_vendors')->nullable();
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('usd_to_bdt')->nullable();
            $table->decimal('foreign_to_usd')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('vat')->nullable();
            $table->string('business_unit')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->decimal('net_amount')->nullable();
            $table->date('pr_date')->nullable();
            $table->string('purchase_center')->nullable();
            $table->string('remarks')->nullable();
            $table->tinyInteger('is_closed')->default(0)->comment('0, 1');
            $table->integer('closed_by')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->string('closing_remarks')->nullable();
            $table->enum('status', ['Pending', 'WIP', 'Closed'])->default('Pending');
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
