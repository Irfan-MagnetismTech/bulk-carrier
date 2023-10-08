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
        Schema::create('ops_charterer_contracts', function (Blueprint $table) {
            $table->id();
            $table->enum('contract_type');
            $table->bigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->bigInteger('ops_charterer_profile_id');
            $table->foreign('ops_charterer_profile_id')->references('id')->on('ops_charterer_profiles');
            $table->string('country');
            $table->string('address')->nullable();
            $table->string('billing_address');
            $table->string('email');
            $table->string('billing_email');
            $table->string('contact_no');
            $table->bigInteger('bank_branche_id');
            $table->string('attachment')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('routing_no')->nullable();
            $table->string('currency')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('port_code')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('business_unit')->nullable();
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
        Schema::dropIfExists('ops_charterer_contracts');
    }
};
