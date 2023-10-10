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
        Schema::create('crw_vessel_required_crew_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crw_vessel_required_crew_id');
            $table->foreign('crw_vessel_required_crew_id', 'fk_crw_vessel_required_crew_id')->references('id')->on('crw_vessel_required_crews')->cascadeOnDelete();            
			$table->unsignedInteger('crw_rank_id');
			$table->integer('required_manpower');
			$table->text('eligibility')->nullable();
			$table->text('remarks')->nullable();            
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
        Schema::dropIfExists('crw_vessel_required_crew_lines');
    }
};
