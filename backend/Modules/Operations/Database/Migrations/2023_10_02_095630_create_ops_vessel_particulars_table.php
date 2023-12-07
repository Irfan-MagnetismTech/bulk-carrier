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
            $table->string('vessel_type')->nullable();
            $table->string('class_no')->nullable();
            $table->string('loa')->nullable();
            $table->string('depth')->nullable();
            $table->string('ecdis_type')->nullable();
            $table->string('maker_ssas')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('name')->nullable();
            $table->string('previous_name')->nullable();
            $table->string('short_code')->unique();
            $table->string('manager')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('classification')->nullable();
            $table->string('flag')->nullable();
            $table->string('previous_flag')->nullable()->nullable();
            $table->string('port_of_registry')->nullable();            
            $table->string('nrt')->nullable();
            $table->string('dwt')->nullable();
            $table->string('imo')->nullable();
            $table->string('grt')->nullable();
            $table->string('official_number')->nullable();
            $table->dateTime('keel_laying_date')->nullable();
            $table->date('launching_date')->nullable();
            $table->string('mmsi')->nullable();
            $table->year('year_built')->nullable();
            $table->float('capacity', 20, 2)->nullable();
            $table->string('total_cargo_hold')->nullable();
            $table->float('tues_capacity', 20, 2)->nullable();
            $table->float('overall_length', 20, 2)->nullable();
            $table->float('overall_width', 20, 2)->nullable();
            $table->float('depth_moulded', 20, 2)->nullable();
            $table->string('bhp')->nullable();
            $table->string('email')->nullable();
            $table->string('lbc')->nullable();
            $table->text('attachment')->nullable(); 
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
        Schema::dropIfExists('ops_vessel_particulars');
    }
};
