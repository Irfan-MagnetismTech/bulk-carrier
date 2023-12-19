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

        Schema::create('ops_customer_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_customer_invoice_id')->constrained('ops_customer_invoices')->onDelete('cascade');
            $table->string('charge_or_deduct')->nullable();
            $table->string('particular')->nullable();
            $table->string('cost_unit')->nullable();
            $table->string('currency')->nullable();
            $table->float('quantity', 20, 2)->nullable();
            $table->float('rate', 20, 2)->nullable();
            $table->float('exchange_rate_bdt', 20, 2)->nullable();
            $table->float('exchange_rate_usd', 20, 2)->nullable();
            $table->float('amount', 20, 2)->nullable();
            $table->float('amount_bdt', 20, 2)->nullable();
            $table->float('amount_usd', 20, 2)->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL', 'ALL'])->nullable();
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
        Schema::dropIfExists('ops_customer_invoice_lines');
    }
};
