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
            $table->string('charge_or_deduct');
            $table->string('particular');
            $table->string('cost_unit');
            $table->string('currency');
            $table->float('quantity');
            $table->float('rate');
            $table->float('exchange_rate_bdt')->nullable();
            $table->float('exchange_rate_usd')->nullable();
            $table->float('amount')->nullable();
            $table->float('amount_bdt')->nullable();
            $table->float('amount_usd')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL', 'BOTH'])->nullable();
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
