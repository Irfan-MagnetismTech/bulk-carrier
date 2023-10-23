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
        Schema::create('ops_voyage_budget_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->unsignedBigInteger('ops_voyage_id');
            $table->foreign('ops_voyage_id')->references('id')->on('ops_voyages');
            $table->unsignedBigInteger('ops_voyage_budget_id');
            $table->foreign('ops_voyage_budget_id')->references('id')->on('ops_voyage_budgets');
            $table->unsignedBigInteger('ops_expense_head_id')->nullable();
            $table->foreign('ops_expense_head_id')->references('id')->on('ops_expense_heads');
            $table->string('perticular')->nullable();
            $table->string('percentage')->nullable();
            $table->string('currency')->nullable();
            $table->float('amount')->nullable();
            $table->float('exchange_rate_bdt')->nullable();
            $table->float('exchange_rate_usd')->nullable();
            $table->float('amount_bdt')->nullable();
            $table->float('amount_usd')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('ops_voyage_budget_entries');
    }
};
