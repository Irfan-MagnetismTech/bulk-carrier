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
        Schema::create('crw_incidents', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('ops_vessel_id');
			$table->date('date_time');
			$table->string('type'); // example : 
			$table->string('location');
			$table->string('reported_by');
			$table->string('attachment')->nullable();
			$table->text('description')->nullable();            
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
        Schema::dropIfExists('crw_incidents');
    }
};
