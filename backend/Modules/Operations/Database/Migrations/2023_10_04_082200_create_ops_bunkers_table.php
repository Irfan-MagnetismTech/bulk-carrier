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
        Schema::create('ops_bunkers', function (Blueprint $table) {
            $table->id();
            $table->string('bunkerable_type');
            $table->bigInteger('bunkerable_id');
            $table->bigInteger('scm_vendor_id')->nullable();  
            $table->bigInteger('scm_material_id')->nullable();        
            // $table->foreignId('scm_vendor_id')->constrained('scm_vendors')->onDelete('cascade');
            // $table->foreignId('scm_material_id')->constrained('scm_materials')->onDelete('cascade');
            $table->string('unit')->nullable();
            $table->float('quantity')->nullable();
            $table->float('requested_quantity')->nullable();
            $table->float('exchange_rate_bdt')->nullable();
            $table->float('exchange_rate_usd')->nullable();
            $table->float('rate')->nullable();
            $table->float('amount')->nullable();
            $table->float('opening_balance')->nullable();
            $table->float('amount_usd')->nullable();
            $table->float('amount_bdt')->nullable();
            $table->float('fuel_con_24h')->nullable();
            $table->float('fuel_con_voyage')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('ops_bunkers');
    }
};
