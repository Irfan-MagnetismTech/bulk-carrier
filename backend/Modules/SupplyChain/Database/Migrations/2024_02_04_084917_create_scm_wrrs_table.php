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
        Schema::create('scm_wrrs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->date('date')->nullable();
            $table->string('wo_no')->nullable();
            $table->date('receive_date')->nullable();
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses');
            $table->bigInteger('purchase_center')->nullable();
            $table->text('remarks')->nullable();
            $table->text('qc_remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable();
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
        Schema::dropIfExists('scm_wrrs');
    }
};
