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
        Schema::create('ops_charterer_invoice_voyages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_charterer_invoice_id')->constrained('ops_charterer_invoices')->onDelete('cascade');
            $table->bigInteger('ops_voyage_id')->nullable();
            $table->string('cargo_quantity')->nullable();
            $table->string('initial_loading_point')->nullable();
            $table->string('final_loading_point')->nullable();
            $table->double('rate_per_mt')->nullable();
            $table->double('total_amount')->nullable();
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
        Schema::dropIfExists('ops_charterer_invoice_voyages');
    }
};
