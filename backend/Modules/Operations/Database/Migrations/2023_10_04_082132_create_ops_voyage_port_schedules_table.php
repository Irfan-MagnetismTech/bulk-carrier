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
            $table->foreignId('ops_voyage_id')->constrained('ops_voyages')->onDelete('cascade');
            $table->string('port_code');
            $table->dateTime('eta')->nullable();
            $table->dateTime('etb')->nullable();
            $table->dateTime('etd')->nullable();
            $table->dateTime('ata')->nullable();
            $table->dateTime('atb')->nullable();
            $table->dateTime('atd')->nullable();
            $table->string('load_commence')->nullable();
            $table->dateTime('load_complete')->nullable();
            $table->string('unload_commence')->nullable();
            $table->dateTime('unload_complete')->nullable();
            $table->string('operation_type');
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
        Schema::dropIfExists('ops_voyage_port_schedules');
    }
};
