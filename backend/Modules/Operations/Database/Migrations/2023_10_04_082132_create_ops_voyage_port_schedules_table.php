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
        Schema::create('ops_voyage_port_schedules', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('ops_voyage_id')->constrained('ops_voyages');
            $table->string('port_code');
            $table->dateTime('eta');
            $table->dateTime('etb');
            $table->dateTime('etd');
            $table->string('load_commence')->nullable();
            $table->dateTime('load_complete')->nullable();
            $table->string('unload_commence')->nullable();
            $table->dateTime('unload_complete')->nullable();
            $table->string('operation_type');
            $table->string('business_unit')->nullable();
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
        Schema::dropIfExists('ops_voyage_port_schedules');
    }
};
