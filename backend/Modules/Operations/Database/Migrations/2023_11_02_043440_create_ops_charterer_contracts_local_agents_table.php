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
        Schema::create('ops_charterer_contracts_local_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_charterer_contract_id');
            // $table->foreign('ops_charterer_contract_id')->references('id')->on('ops_charterer_contracts');
            $table->string('port_code')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('agent_billing_name')->nullable();
            $table->string('agent_billing_email')->nullable();
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
        Schema::dropIfExists('ops_charterer_contracts_local_agents');
    }
};
