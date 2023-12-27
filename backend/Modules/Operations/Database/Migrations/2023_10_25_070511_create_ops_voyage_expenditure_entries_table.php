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
            $table->id();
            $table->foreignId('ops_voyage_expenditure_id')->constrained('ops_voyage_expenditures')->cascadeOnDelete();
            $table->foreignId('ops_expense_head_id')->constrained('ops_expense_heads')->cascadeOnDelete();
            $table->string('particular')->nullable();
            $table->string('type')->default('expense')->comment('expense')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->float('quantity', 20, 2)->nullable();
            $table->float('rate', 20, 2)->nullable();
            $table->float('amount', 20, 2)->nullable();
            $table->float('amount_usd', 20, 2)->nullable();    
            $table->float('amount_bdt', 20, 2)->nullable();  
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
