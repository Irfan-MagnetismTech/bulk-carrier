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
        Schema::create('ops_voyage_boat_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_voyage_id')->constrained('ops_voyages')->onDelete('cascade');
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->string('type');
            $table->string('vessel_draft');
            $table->string('water_density');
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
        Schema::dropIfExists('ops_voyage_boat_notes');
    }
};
