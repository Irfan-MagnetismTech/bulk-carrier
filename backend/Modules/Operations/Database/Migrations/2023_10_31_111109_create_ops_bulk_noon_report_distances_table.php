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
        Schema::create('ops_bulk_noon_report_distances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_bulk_noon_report_id');
            // $table->unsignedBigInteger('ops_bulk_noon_report_id');
            // $table->foreign('ops_bulk_noon_report_id')->references('id')->on('ops_bulk_noon_reports');
            $table->string('cp_ordered_speed')->nullable();
            $table->string('reported_speed')->nullable();
            $table->float('observed_distance')->nullable();
            $table->float('engine_distance')->nullable();
            $table->string('main_engine_revs')->nullable();
            $table->string('slip_percent')->nullable();
            $table->string('salinity')->nullable();
            $table->string('ballast')->nullable();
            $table->float('average_rpm')->nullable();
            $table->string('fwd_draft')->nullable();
            $table->string('mild_draft')->nullable();
            $table->string('aft_draft')->nullable();
            $table->string('heading')->nullable();
            $table->time('steaming_hours')->nullable();
            $table->string('s_dwt')->nullable();
            $table->string('s_displacement')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']); 
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
        Schema::dropIfExists('ops_bulk_noon_report_distances');
    }
};
