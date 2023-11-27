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
        Schema::create('ops_charterer_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_charterer_invoice_id')->constrained('ops_charterer_invoices')->onDelete('cascade');
            $table->string('charge_or_deduct')->nullable();
            $table->string('particular')->nullable();
            $table->string('cost_unit')->nullable();
            $table->string('currency')->nullable();
            $table->float('quantity')->nullable();
            $table->float('rate')->nullable();
            $table->float('exchange_rate_bdt')->nullable();
            $table->float('exchange_rate_usd')->nullable();
            $table->float('amount')->nullable();
            $table->float('amount_bdt')->nullable();
            $table->float('amount_usd')->nullable();
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
        Schema::dropIfExists('ops_charterer_invoice_lines');
    }
};
