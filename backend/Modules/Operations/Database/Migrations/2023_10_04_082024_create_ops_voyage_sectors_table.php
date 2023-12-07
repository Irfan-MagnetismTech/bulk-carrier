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
        Schema::create('ops_voyage_sectors', function (Blueprint $table) {
            $table->id();       
            $table->foreignId('ops_voyage_id')->constrained('ops_voyages')->onDelete('cascade');
            $table->string('loading_point')->nullable(); 
            $table->string('unloading_point')->nullable(); 
            $table->float('rate')->nullable(); 
            $table->float('initial_survey_qty', 20, 2)->nullable(); 
            $table->float('approx_amount', 20, 2)->nullable(); 
            $table->float('discount', 20, 2)->nullable(); 
            $table->float('discount_amount', 20, 2)->nullable(); 
            $table->float('approx_amount_after_disscount', 20, 2)->nullable(); 
            $table->float('final_survey_qty', 20, 2)->nullable(); 
            $table->float('final_received_qty', 20, 2)->nullable(); 
            $table->float('boat_note_qty', 20, 2)->nullable(); 
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable(); 
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
        Schema::dropIfExists('ops_voyage_sectors');
    }
};
