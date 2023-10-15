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
        Schema::create('crw_agency_bills', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_agency_id');
			$table->unsignedBigInteger('crw_agency_contract_id');
			$table->date('applied_date');
			$table->date('invoice_date');
			$table->string('invoice_no');
			$table->string('invoice_type'); //example: Crew Onboarding, Agency Comission
			$table->string('invoice_currency');
			$table->decimal('invoice_amount');
			$table->decimal('grand_total');
			$table->decimal('discount')->default(0.00);
			$table->decimal('net_amount');
			$table->text('remarks')->nullable();            
			$table->enum('business_unit', ['PSML', 'TSLL']);
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
        Schema::dropIfExists('crw_agency_bills');
    }
};
