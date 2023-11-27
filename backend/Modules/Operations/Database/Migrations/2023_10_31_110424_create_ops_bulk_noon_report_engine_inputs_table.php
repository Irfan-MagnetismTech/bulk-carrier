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
        Schema::create('ops_bulk_noon_report_engine_inputs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_bulk_noon_report_id');
            // $table->unsignedBigInteger('ops_bulk_noon_report_id');
            // $table->foreign('ops_bulk_noon_report_id')->references('id')->on('ops_bulk_noon_reports');
            $table->string('type')->nullable();
            $table->string('engine_unit')->nullable();
            $table->string('pco')->nullable();
            $table->string('rack')->nullable();
            $table->string('exh_temp')->nullable();
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
        Schema::dropIfExists('ops_bulk_noon_report_engine_inputs');
    }
};
