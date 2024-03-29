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
        Schema::create('crw_payroll_batches', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('ops_vessel_id');
			$table->integer('month_no');
			$table->year('year');
			$table->string('compensation_type');
			$table->date('start_date');
			$table->date('end_date');
			$table->date('process_date');
			$table->decimal('net_payment');
			$table->string('currency');
			$table->integer('working_days');
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
        Schema::dropIfExists('crw_payroll_batches');
    }
};
