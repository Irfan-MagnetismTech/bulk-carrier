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
        Schema::create('crw_salary_structure_breakdowns', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_salary_structure_id')->constrained('crw_salary_structures', 'id')->cascadeOnDelete();
			$table->enum('component_type', ['Earnings', 'Deductions']);
			$table->string('component_category'); // Salary/Allowance/Deduction/Bonus
			$table->string('component_name'); // Basic Salary 
			$table->decimal('amount');
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
        Schema::dropIfExists('crw_salary_structure_breakdowns');
    }
};
