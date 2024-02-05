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
        Schema::create('scm_stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_material_id')->constrained('scm_materials');
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses');
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('stockable_type')->nullable();
            $table->bigInteger('stockable_id')->nullable();
            $table->string('recievable_type')->nullable();
            $table->bigInteger('recievable_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('gross_unit_price')->nullable();
            $table->decimal('gross_foreign_unit_price')->nullable();
            $table->bigInteger('net_unit_price')->nullable();
            $table->bigInteger('net_foreign_unit_price')->nullable();
            $table->string('currency')->nullable();
            $table->string('composite_key')->nullable();
            $table->decimal('exchange_rate')->nullable();
            $table->string('business_unit')->comment('ALL, PSML,TSLL')->nullable();
            $table->timestamp('date')->nullable();
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
        Schema::dropIfExists('scm_stock_ledgers');
    }
};
