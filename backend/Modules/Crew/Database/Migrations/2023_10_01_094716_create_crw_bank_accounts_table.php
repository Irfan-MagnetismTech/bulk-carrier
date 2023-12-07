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
        Schema::create('crw_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crw_crew_id');
			$table->string('bank_name');
			$table->string('branch_name');
			$table->string('routing_number')->nullable();
			$table->string('account_name'); // account holder name 
			$table->string('account_number');
			$table->string('benificiary_name');
			$table->string('attachment')->nullable();
			$table->tinyInteger('is_active')->default(true)->nullable();
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
        Schema::dropIfExists('crw_bank_accounts');
    }
};
