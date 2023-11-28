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
        Schema::create('ops_bulk_noon_report_consumption_heads', function (Blueprint $table) {
            $table->id();           
            $table->unsignedBigInteger('ops_bulk_noon_report_id');
            $table->unsignedBigInteger('ops_bulk_noon_report_consumption_id');
            $table->string('type')->nullable();
            $table->float('amount')->nullable();
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
        Schema::dropIfExists('ops_bulk_noon_report_consumption_heads');
    }
};
