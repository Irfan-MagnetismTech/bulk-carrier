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
        Schema::create('acc_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acc_cost_center_id')->nullable();
            $table->string('year_month', 7);
            $table->decimal('total_salary', 16, 2);
			$table->text('remarks')->nullable();                        
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
        Schema::dropIfExists('acc_salaries');
    }
};
