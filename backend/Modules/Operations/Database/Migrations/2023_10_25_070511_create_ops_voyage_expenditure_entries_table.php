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
        Schema::create('ops_voyage_expenditure_entries', function (Blueprint $table) {
            $table->foreignId('ops_voyage_expenditure_id')->constrained('ops_voyage_expenditures')->cascadeOnDelete();
            $table->foreignId('particular_id')->constrained('ops_expense_heads')->cascadeOnDelete();
            $table->string('type')->default('expense')->comment('expense');
            $table->string('invoice_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('rate')->nullable();
            $table->float('amount')->nullable();
            $table->float('amount_bdt')->nullable();    
            $table->string('attachment')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('ops_voyage_expenditure_entries');
    }
};
