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
        Schema::create('ops_bulk_noon_report_cargo_tanks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_bulk_noon_report_id');
            $table->foreign('ops_bulk_noon_report_id')->references('id')->on('ops_bulk_noon_reports');
            $table->string('cargo_tanks')->nullable();
            $table->string('liq_level')->nullable();
            $table->string('pressure')->nullable();
            $table->string('vapor_temp')->nullable();
            $table->string('liq_temp')->nullable();
            $table->float('quantity_mt')->nullable();
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
        Schema::dropIfExists('ops_bulk_noon_report_cargo_tanks');
    }
};
