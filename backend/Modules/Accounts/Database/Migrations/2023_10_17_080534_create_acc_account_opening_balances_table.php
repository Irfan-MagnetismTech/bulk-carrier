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
        Schema::create('acc_account_opening_balances', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('acc_cost_center_id');
			$table->unsignedBigInteger('acc_account_id');
			$table->date('date');
			$table->decimal('dr_amount', 10, 2)->nullable();
			$table->decimal('cr_amount', 10, 2)->nullable();
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
        Schema::dropIfExists('acc_account_opening_balances');
    }
};
