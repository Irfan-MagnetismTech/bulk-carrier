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
        Schema::create('ops_vessels', function (Blueprint $table) {
            $table->id();            
            $table->string('vessel_type');
            $table->string('name');
            $table->string('previous_name')->nullable();
            $table->string('short_code')->unique();
            $table->string('call_sign');
            $table->string('owner_name');
            $table->string('manager');
            $table->string('classification');
            $table->string('flag');
            $table->string('previous_flag')->nullable();
            $table->string('port_of_registry');
            $table->date('delivery_date')->nullable();
            $table->string('nrt');
            $table->string('dwt');
            $table->string('imo');
            $table->string('grt');
            $table->string('official_number');
            $table->date('keel_laying_date');
            $table->date('launching_date');
            $table->string('mmsi');
            $table->float('overall_length');
            $table->float('overall_width');
            $table->year('year_built');
            $table->float('capacity');
            $table->string('total_cargo_hold');
            $table->string('live_tracking_config')->nullable();
            $table->text('remarks')->nullable();  
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
        Schema::dropIfExists('ops_vessels');
    }
};
