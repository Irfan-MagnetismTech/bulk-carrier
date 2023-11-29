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
            $table->string('port');
            $table->string('currency')->nullable();
            $table->string('rate')->nullable();
            $table->float('total_usd')->nullable();
            $table->float('total_bdt')->nullable();
            $table->longText('expense_json')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
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