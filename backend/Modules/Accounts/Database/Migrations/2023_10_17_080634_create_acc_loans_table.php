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
        Schema::create('acc_loans', function (Blueprint $table) {
            $table->id();
            $table->string('loanable_type');
            $table->unsignedBigInteger('loanable_id');
            $table->string('loan_type');
            $table->string('loan_number');
            $table->string('loan_name');
            $table->decimal('total_sanctioned', 20, 2);
            $table->decimal('sanctioned_limit', 20, 2);
            $table->number('total_installment');
            $table->float('interest_rate', );
            $table->string('opening_date');
            $table->string('maturity_date');
            $table->string('emi_date');
            $table->string('emi_amount');
            $table->string('loan_purpose');
            $table->string('mortgage')->nullable();
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('acc_loans');
    }
};
