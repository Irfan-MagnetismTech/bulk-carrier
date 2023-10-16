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
            $table->foreignId('ops_voyage_id')->constrained('ops_voyages')->onDelete('cascade');
            $table->string('contract_type');
            $table->dateTime('bill_from');
            $table->dateTime('bill_till');
            $table->bigInteger('total_days');
            $table->float('total_amount');
            $table->float('others_billable_amount');
            $table->float('service_fee_deduction_amount');
            $table->string('discount_unit');
            $table->float('discounted_amount');
            $table->float('grand_total');
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
