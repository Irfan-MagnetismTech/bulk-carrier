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
        Schema::create('crw_attendances', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('ops_vessel_id');
            $table->string('year_month', 7);
			$table->integer('working_days');
			$table->integer('total_crews');
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
        Schema::dropIfExists('crw_attendances');
    }
};
