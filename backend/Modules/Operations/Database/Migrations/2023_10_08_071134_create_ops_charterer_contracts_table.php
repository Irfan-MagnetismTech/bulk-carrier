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
            $table->enum('contract_type', ['Voyage Wise', 'Day Wise']);
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->foreignId('ops_charterer_profile_id')->constrained('ops_charterer_profiles')->onDelete('cascade');
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('email')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('contact_no')->nullable();
            $table->bigInteger('bank_branche_id')->nullable();
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
        Schema::dropIfExists('ops_charterer_contracts');
    }
};
