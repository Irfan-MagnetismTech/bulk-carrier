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
            $table->unsignedBigInteger('crw_payroll_batch_id');
            $table->unsignedBigInteger('crw_crew_id');
            $table->unsignedBigInteger('crw_attendance_line_id');
            $table->unsignedBigInteger('crw_salary_structure_id');
            $table->decimal('payable_amount', 16, 2)->nullable(); //as per attendance
            $table->decimal('total_earnings', 16, 2)->nullable();;
            $table->decimal('total_deductions', 16, 2)->nullable();;
            $table->decimal('net_payable_amount', 16, 2); // payable amount + total earnings + total deduction 
            $table->date('payment_date')->nullable();
            $table->unsignedBigInteger('crw_bank_account_id')->nullable();
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
