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
        Schema::create('ops_month_wise_expense_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->date('from_date');
            $table->date('till_date');
            $table->float('grand_total_cost');
            $table->float('left_over_amount')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']); 
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
        Schema::dropIfExists('ops_month_wise_expense_reports');
    }
};
