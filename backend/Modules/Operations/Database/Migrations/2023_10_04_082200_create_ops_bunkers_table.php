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
            $table->float('quantity', 20, 2)->nullable();
            $table->float('requested_quantity', 20, 2)->nullable();
            $table->float('exchange_rate_bdt', 20, 2)->nullable();
            $table->float('exchange_rate_usd', 20, 2)->nullable();
            $table->float('rate', 20, 2)->nullable();
            $table->float('amount', 20, 2)->nullable();
            $table->float('opening_balance', 20, 2)->nullable();
            $table->float('amount_usd', 20, 2)->nullable();
            $table->float('amount_bdt', 20, 2)->nullable();
            $table->float('fuel_con_24h', 20, 2)->nullable();
            $table->float('fuel_con_voyage', 20, 2)->nullable();
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
