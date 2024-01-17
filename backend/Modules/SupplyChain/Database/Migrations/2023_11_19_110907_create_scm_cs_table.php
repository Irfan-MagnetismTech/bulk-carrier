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
        Schema::create('scm_cs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->unsignedBigInteger('scm_warehouse_id')->nullable();
            $table->unsignedBigInteger('acc_cost_center_id')->nullable();
            $table->date('effective_date');
            $table->date('expire_date');
            $table->text('special_instructions')->nullable();
            $table->string('priority')->nullable();
            $table->string('required_days')->nullable();
            $table->string('purchase_center')->nullable();
            $table->string('selection_ground')->nullable();
            $table->date('auditor_remarks_date')->nullable();
            $table->string('auditor_remarks')->nullable();
            $table->string('status')->nullable();
            $table->string('business_unit')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->integer('is_foreign')->nullable();
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
        Schema::dropIfExists('scm_cs');
    }
};
