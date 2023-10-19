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
        Schema::create('ops_vessel_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->string('vessel_type');
            $table->string('class_no');
            $table->string('loa');
            $table->string('depth');
            $table->string('ecdis_type');
            $table->string('maker_ssas');
            $table->string('engine_type');
            $table->string('previous_name')->nullable();
            $table->string('call_sign');
            $table->string('owner_name');
            $table->string('classification');
            $table->string('flag');
            $table->string('previous_flag')->nullable();
            $table->string('port_of_registry');            
            $table->string('nrt');
            $table->string('dwt');
            $table->string('imo');
            $table->string('grt');
            $table->string('official_number');
            $table->dateTime('keel_laying_date');
            $table->string('mmsi');
            $table->year('year_built');
            $table->float('tues_capacity');
            $table->float('overall_length');            
            $table->float('overall_width');
            $table->float('depth_moulded');
            $table->string('bhp');
            $table->string('email');
            $table->string('lbc');
            $table->text('attachment');
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
        Schema::dropIfExists('ops_vessel_particulars');
    }
};
