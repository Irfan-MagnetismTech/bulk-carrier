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
        Schema::create('ops_charterer_contracts_financial_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_charterer_contract_id');
            // $table->foreign('ops_charterer_contract_id')->references('id')->on('ops_charterer_contracts');
            $table->bigInteger('ops_voyage_id')->nullable();
            $table->bigInteger('ops_cargo_tariff_id')->nullable();
            $table->string('credit_days')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_till')->nullable();
            $table->string('status')->nullable();
            $table->float('approximate_load_amount')->nullable();
            $table->float('per_mt_charge')->nullable();
            $table->float('per_day_charge')->nullable();
            $table->float('cleaning_fee')->nullable();
            $table->float('cancellation_fee')->nullable();
            $table->float('others_fee')->nullable();
            $table->float('per_ton_charge')->nullable();
            $table->string('bunker_provider')->nullable();
            
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
        Schema::dropIfExists('ops_charterer_contracts_financial_terms');
    }
};
