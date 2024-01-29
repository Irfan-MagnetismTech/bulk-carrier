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
        Schema::create('scm_wo_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_wo_line_id')->nullable();
            $table->bigInteger('scm_service_id')->nullable();
            $table->bigInteger('scm_wo_id')->nullable();
            $table->text('description')->nullable();
            $table->date('required_date')->nullable();
            $table->float('quantity', 20, 2)->nullable();
            $table->float('rate', 20, 2)->nullable();
            $table->float('total_price', 20, 2)->nullable();
            $table->string('wr_composite_key')->nullable();
            $table->string('wo_composite_key')->nullable();
            $table->string('wcs_composite_key')->nullable();
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
        Schema::dropIfExists('wo_items');
    }
};
