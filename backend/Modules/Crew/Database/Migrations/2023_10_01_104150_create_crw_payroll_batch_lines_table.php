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
        Schema::create('crw_payroll_batch_lines', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('crw_crew_id');
            $table->unsignedBigInteger('crw_payroll_batch_id');
            $table->unsignedBigInteger('crw_salary_structure_id');
            $table->string('attendance_line_composite')->nullable();
            $table->decimal('gross_salary');
            $table->integer('payable_days')->nullable();
            $table->decimal('payable_amount')->nullable(); //as per attendance
            $table->decimal('total_earnings')->nullable();;
            $table->decimal('total_deductions')->nullable();;
            $table->decimal('net_payable_amount');
            $table->date('payment_date')->nullable();;
            $table->unsignedBigInteger('crw_bank_account_id')->nullable();;

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
        Schema::dropIfExists('crw_payroll_batch_lines');
    }
};
