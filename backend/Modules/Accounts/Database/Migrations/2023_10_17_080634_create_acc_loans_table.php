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
            $table->integer('total_installment');
            $table->float('interest_rate', 5, 2);
            $table->string('opening_date');
            $table->date('maturity_date');
            $table->date('emi_date');
            $table->decimal('emi_amount');
            $table->text('loan_purpose');
            $table->text('mortgage')->nullable();
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
        Schema::dropIfExists('acc_loans');
    }
};
