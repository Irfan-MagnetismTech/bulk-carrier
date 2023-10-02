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
			$table->date('date_of_joining');
			$table->string('port_of_joining'); // port code 
			$table->string('approx_duration'); //4 months (+/-1)
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
