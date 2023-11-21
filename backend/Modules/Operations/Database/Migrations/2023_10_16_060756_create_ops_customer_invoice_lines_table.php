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
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->foreignId('ops_voyage_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->float('amount');
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
