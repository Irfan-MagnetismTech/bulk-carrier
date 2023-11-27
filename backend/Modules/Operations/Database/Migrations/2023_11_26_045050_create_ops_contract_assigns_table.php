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
        Schema::create('ops_contract_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id')->nullable();
            $table->unsignedBigInteger('ops_voyage_id')->nullable();
            $table->unsignedBigInteger('ops_cargo_tariff_id')->nullable();
            $table->unsignedBigInteger('ops_customer_id')->nullable();
            $table->unsignedBigInteger('ops_charterer_profile_id')->nullable();
            $table->unsignedBigInteger('ops_charterer_contract_id')->nullable();
            $table->date('assign_date')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable();
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
        Schema::dropIfExists('ops_contract_assigns');
    }
};
