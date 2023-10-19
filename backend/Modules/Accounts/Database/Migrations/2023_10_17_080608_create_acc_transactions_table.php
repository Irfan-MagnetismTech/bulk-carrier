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
        Schema::create('acc_transactions', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('acc_cost_center_id');
			$table->string('voucher_type');
			$table->string('transactionable_type');
			$table->unsignedBigInteger('transactionable_id');
			$table->date('transaction_date');
			$table->string('bill_no')->nullable();
			$table->string('mr_no')->nullable();
			$table->text('narration')->nullable();
			$table->string('instrument_type')->nullable();
			$table->string('instrument_no')->nullable();
			$table->date('instrument_date')->nullable();
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
        Schema::dropIfExists('acc_transactions');
    }
};
