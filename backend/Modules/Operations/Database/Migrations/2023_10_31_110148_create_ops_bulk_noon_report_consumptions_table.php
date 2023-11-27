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
            $table->unsignedBigInteger('ops_bulk_noon_report_id');
            $table->unsignedBigInteger('ops_bunker_id')->nullable();
            $table->foreignId('scm_material_id')->constrained('scm_materials')->onDelete('cascade');
            // $table->unsignedBigInteger('ops_bulk_noon_report_id');
            // $table->foreign('ops_bulk_noon_report_id')->references('id')->on('ops_bulk_noon_reports');
            $table->string('type')->nullable();
            $table->string('consumption')->nullable();
            $table->string('previous_rob')->nullable();
            $table->string('received')->nullable();
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
