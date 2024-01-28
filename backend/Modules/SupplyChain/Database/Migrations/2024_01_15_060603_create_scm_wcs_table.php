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
        Schema::create('scm_wcs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_warehouse_id')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->string('ref_no')->nullable();
            $table->enum('purchase_center', ['Local', 'Foreign','Plant'])->nullable();
            $table->string('special_instruction')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('required_days')->nullable();
            $table->string('selection_ground')->nullable();
            $table->date('auditor_remarks_date')->nullable();
            $table->string('auditor_remarks')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('priority', ['High','Low','Medium'])->nullable();
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
        Schema::dropIfExists('scm_wcs');
    }
};
