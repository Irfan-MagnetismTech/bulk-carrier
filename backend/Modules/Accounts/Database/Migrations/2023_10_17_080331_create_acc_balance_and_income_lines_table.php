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
        Schema::create('acc_balance_and_income_lines', function (Blueprint $table) {
            $table->id();
			$table->enum('line_type', ['base_header', 'balance_header', 'income_header', 'balance_line', 'income_line']);
			$table->string('line_text'); // Asset > Current Asset > Bank Account 
            $table->enum('value_type', ['D', 'C']);
			$table->integer('parent_id')->nullable();
			$table->integer('visible_index')->nullable();
			$table->string('printed_no')->nullable();
			$table->integer('_lft')->nullable();
			$table->integer('_rgt')->nullable();            
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
        Schema::dropIfExists('acc_balance_and_income_lines');
    }
};
