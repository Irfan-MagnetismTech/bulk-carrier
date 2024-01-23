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
            $table->bigInteger('description')->nullable();
            $table->bigInteger('received_date')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('rate')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('scm_wo_id')->nullable();
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
