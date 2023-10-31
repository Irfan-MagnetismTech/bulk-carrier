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
        Schema::create('ops_bulk_noon_report_consumptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_bulk_noon_report_id');
            // $table->unsignedBigInteger('ops_bulk_noon_report_id');
            // $table->foreign('ops_bulk_noon_report_id')->references('id')->on('ops_bulk_noon_reports');
            $table->string('type')->nullable();
            $table->string('previous_rob')->nullable();
            $table->string('received')->nullable();
            $table->float('me')->nullable();
            $table->float('ge')->nullable();
            $table->float('blr')->nullable();
            $table->float('ig')->nullable();
            $table->float('aux')->nullable();
            $table->float('main')->nullable();
            $table->float('me_cyl_oil')->nullable();
            $table->float('ge_sys_oil')->nullable();
            $table->string('rob')->nullable();
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
        Schema::dropIfExists('ops_bulk_noon_report_consumptions');
    }
};
