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
        Schema::create('acc_loan_receiveds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acc_loan_id');
            $table->date('received_date');
            $table->string('payment_method');
            $table->unsignedBigInteger('received_acc_account_id');
            $table->string('instrument_no');
            $table->date('instrument_date');
            $table->decimal('received_amount', 20, 2);
            $table->float('interest_rate', 5, 2);                
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
        Schema::dropIfExists('acc_loan_receiveds');
    }
};
