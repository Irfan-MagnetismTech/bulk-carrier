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
        Schema::create('acc_salary_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('acc_salary_id')->constrained('acc_salaries', 'id')->cascadeOnDelete();
			$table->string('particular');
            $table->decimal('amount', 16, 2);
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
        Schema::dropIfExists('acc_salary_lines');
    }
};
