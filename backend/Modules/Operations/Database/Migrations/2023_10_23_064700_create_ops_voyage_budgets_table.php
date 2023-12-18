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
        Schema::create('ops_voyage_budgets', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->unsignedBigInteger('ops_voyage_id');
            $table->foreign('ops_voyage_id')->references('id')->on('ops_voyages');
            $table->unsignedBigInteger('ops_expense_head_id')->nullable();
            $table->foreign('ops_expense_head_id')->references('id')->on('ops_expense_heads');
            $table->string('title');
            $table->float('total', 20, 2)->nullable();
            $table->string('currency');
            $table->float('exchange_rate_usd', 20, 2)->nullable();
            $table->float('exchange_rate_bdt', 20, 2)->nullable();
            $table->dateTime('effective_from')->nullable();
            $table->dateTime('effective_till')->nullable();


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
        Schema::dropIfExists('ops_voyage_budgets');
    }
};
