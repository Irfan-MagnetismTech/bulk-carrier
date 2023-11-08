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
            $table->string('rate')->nullable(); 
            $table->float('initial_survey_qty')->nullable(); 
            $table->float('approx_amount')->nullable(); 
            $table->float('discount')->nullable(); 
            $table->float('discount_amount')->nullable(); 
            $table->float('approx_amount_after_disscount')->nullable(); 
            $table->float('final_survey_qty')->nullable(); 
            $table->float('final_received_qty')->nullable(); 
            $table->float('boat_note_qty')->nullable(); 
            $table->float('latest_qty')->nullable(); 
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
