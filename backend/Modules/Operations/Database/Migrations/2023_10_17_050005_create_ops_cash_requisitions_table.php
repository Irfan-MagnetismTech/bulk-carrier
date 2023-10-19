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
        Schema::create('ops_cash_requisitions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('requisition_by');
            $table->string('serial');
            $table->string('unit');
            $table->float('amount')->nullable();
            $table->string('amount_in_words');
            $table->float('amount_bdt')->nullable();
            $table->float('amount_usd')->nullable();
            $table->text('description');
            $table->string('salary_unit');
            $table->string('pf_no');
            $table->string('mobile_no');
            $table->string('purpose');
            $table->string('preferred_adjustment_method');
            $table->string('approximate_adjustment_date');
            $table->string('status');
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable();             
            $table->date('approved_date')->nullable();
            $table->date('received_date')->nullable();
            $table->float('received_amount')->nullable();
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
        Schema::dropIfExists('ops_cash_requisitions');
    }
};
