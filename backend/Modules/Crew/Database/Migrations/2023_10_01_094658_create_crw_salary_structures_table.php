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
			$table->unsignedBigInteger('promotion_id')->nullable();
			$table->integer('increment_sequence')->nullable();
			$table->date('effective_date');
			$table->string('currency');
			$table->decimal('gross_salary', 16, 2);
			$table->decimal('addition', 16, 2)->default(0);
			$table->decimal('deduction', 16, 2)->default(0);
			$table->decimal('net_amount', 16, 2);
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
