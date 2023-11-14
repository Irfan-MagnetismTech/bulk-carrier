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
        Schema::create('mnt_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->unsignedBigInteger('mnt_survey_item_id');
            $table->foreign('mnt_survey_item_id')->references('id')->on('mnt_survey_items');
            $table->unsignedBigInteger('mnt_survey_type_id');
            $table->foreign('mnt_survey_type_id')->references('id')->on('mnt_survey_types');
            $table->string('short_code', 255)->nullable();
            $table->string('survey_name', 255);
            $table->date('range_date_from');
            $table->date('range_date_to');
            $table->date('assigned_date');
            $table->date('due_date');
            $table->enum('business_unit',['TSLL','PSML']);
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
        Schema::dropIfExists('mnt_surveys');
    }
};
