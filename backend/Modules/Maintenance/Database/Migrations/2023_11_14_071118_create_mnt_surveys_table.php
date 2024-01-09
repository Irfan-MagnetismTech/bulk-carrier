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
            $table->unsignedBigInteger('mnt_survey_item_id');
            $table->foreign('mnt_survey_item_id')->references('id')->on('mnt_survey_items');
            $table->unsignedBigInteger('mnt_survey_type_id');
            $table->foreign('mnt_survey_type_id')->references('id')->on('mnt_survey_types');
            $table->string('short_code', 255)->nullable();
            $table->string('survey_name', 255);
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
