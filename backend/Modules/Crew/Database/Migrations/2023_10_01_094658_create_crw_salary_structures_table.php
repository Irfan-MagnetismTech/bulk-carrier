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
        Schema::create('crw_salary_structures', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_crew_id');
			$table->integer('increment_sequence');
			$table->date('effective_date');
			$table->unsignedBigInteger('promotion_id')->nullable();
			$table->string('currency');
			$table->date('gross_salary');
			$table->tinyInteger('is_active');
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
        Schema::dropIfExists('crw_salary_structures');
    }
};
