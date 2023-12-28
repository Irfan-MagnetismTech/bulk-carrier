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
        Schema::create('scm_mmrs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->date('date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->foreignId('from_warehouse_id')->constrained('scm_warehouses');
            $table->foreignId('to_warehouse_id')->constrained('scm_warehouses');
            $table->string('requested_by')->nullable();
            $table->string('requested_for')->nullable();
            $table->text('remarks')->nullable();
            $table->string('business_unit')->comment('TSLL,PSML')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
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
        Schema::dropIfExists('scm_mmrs');
    }
};
