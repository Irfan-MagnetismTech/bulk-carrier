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
        Schema::create('acc_bank_accounts', function (Blueprint $table) {
            $table->id();
			$table->string('bank_name');
            $table->string('branch_name');
			$table->string('account_type'); // SOD / CA
            $table->string('account_name');
			$table->string('account_number');
			$table->string('routing_number')->nullable();
			$table->string('contact_number')->nullable();
			$table->date('opening_date')->nullable();
			$table->float('opening_balance', 20, 2);
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
        Schema::dropIfExists('acc_bank_accounts');
    }
};
