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
        Schema::create('appraisal_form_line_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_no');            
            $table->unsignedBigInteger('appraisal_form_line_id');
            $table->foreign('appraisal_form_line_id', 'fk_appraisal_form_line_id')->references('id')->on('appraisal_form_lines')->cascadeOnDelete();            
            $table->string('aspect');
            $table->text('description')->nullable();
            $table->enum('answer_type', ['Number', 'Boolean', 'Grade', 'Other']);
            $table->string('item_composite')->nullable();
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
        Schema::dropIfExists('appraisal_form_line_items');
    }
};
