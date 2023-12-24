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
        Schema::create('ops_voyage_expenditures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_voyage_id')->constrained('ops_voyages', 'id')->nullable();
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels', 'id')->nullable();
            $table->string('port_code')->nullable();
            $table->string('currency')->nullable();
            $table->float('sub_total_bdt', 20, 2)->nullable();
            $table->float('sub_total_usd', 20, 2)->nullable();
            $table->float('discount_bdt', 20, 2)->nullable();
            $table->float('discount_usd', 20, 2)->nullable();
            $table->float('grand_total_bdt', 20, 2)->nullable();
            $table->float('grand_total_usd', 20, 2)->nullable();
            $table->float('exchange_rate_bdt', 20, 2)->nullable();
            $table->float('exchange_rate_usd', 20, 2)->nullable();
            $table->longText('expense_json')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->string('attachment')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']);  
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
        Schema::dropIfExists('ops_voyage_expenditures');
    }
};
