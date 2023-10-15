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
        Schema::create('crw_agency_contracts', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_agency_id');
			$table->string('billing_cycle');
			$table->string('billing_currency');
			$table->date('validity_from');
			$table->date('validity_till');
			$table->text('service_offered');
			$table->text('terms_and_conditions');
			$table->text('remarks')->nullable();
			$table->string('attachment')->nullable();
			$table->string('account_holder_name');
			$table->string('bank_name');
			$table->string('bank_address');
			$table->string('account_no');
			$table->string('swift_code')->nullable();
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
        Schema::dropIfExists('crw_agency_contracts');
    }
};
