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
        Schema::create('crw_crew_assignments', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('ops_vessel_id');
			$table->unsignedBigInteger('crw_crew_id');
			$table->string('position_onboard'); // rank name
			$table->date('joining_date');   
			$table->string('joining_port_code'); // port code 
			$table->string('duration'); //4 months (+/-1)
			$table->enum('status', ['Onboard', 'Complete'])->default('Onboard'); //[assigned/onboard->active->complete]
			$table->date('completion_date')->nullable();   
			$table->string('completion_remarks')->nullable();   
			$table->string('remarks')->nullable();            
			$table->enum('business_unit', ['PSML', 'TSLL']);            
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
        Schema::dropIfExists('crw_crew_assignments');
    }
};
