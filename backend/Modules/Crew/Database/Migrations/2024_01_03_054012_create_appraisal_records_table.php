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
        Schema::create('appraisal_records', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_crew_id');
			$table->unsignedBigInteger('appraisal_form_id');
			$table->unsignedBigInteger('crw_crew_assignment_id');			
			$table->date('appraisal_date');
			$table->integer('age');
			$table->enum('business_unit', ['PSML', 'TSLL']);
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
        Schema::dropIfExists('appraisal_records');
    }
};
