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
        Schema::create('ops_charterer_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_charterer_profile_id')->constrained('ops_charterer_profiles')->onDelete('cascade');
            $table->foreignId('ops_charterer_contract_id')->constrained('ops_charterer_contracts')->onDelete('cascade');
            $table->bigInteger('ops_voyage_id')->nullable();
            $table->string('contract_type')->nullable();
            $table->dateTime('bill_from')->nullable();
            $table->dateTime('bill_till')->nullable();
            $table->bigInteger('total_days')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('per_day_charge')->nullable();
            $table->double('others_billable_amount')->nullable();
            $table->double('service_fee_deduction_amount')->nullable();
            $table->string('discount_unit')->nullable();
            $table->double('discounted_amount')->nullable();
            $table->double('grand_total')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL', 'BOTH'])->nullable();
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
        Schema::dropIfExists('ops_charterer_invoices');
    }
};
