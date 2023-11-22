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
        Schema::create('ops_bulk_noon_report_ports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_bulk_noon_report_id');
            $table->foreign('ops_bulk_noon_report_id')->references('id')->on('ops_bulk_noon_reports');
            $table->string('last_port')->nullable();
            $table->string('next_port')->nullable();
            $table->string('eta')->nullable();
            $table->string('distance_run')->nullable();
            $table->string('dtg')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('ops_bulk_noon_report_ports');
    }
};
