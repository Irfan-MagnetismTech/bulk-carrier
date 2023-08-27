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
            $table->string('name');
            $table->string('code');
            $table->string('ownership');
            $table->string('flag');
            $table->year('year_built');
            $table->string('call_sign');
            $table->string('grt');
            $table->string('nrt');
            $table->string('dwt');
            $table->decimal('speed');
            $table->integer('capacity_tues');
            $table->decimal('reefer_capacity');
            $table->string('imo_number');
            $table->string('classification');
            $table->longText('remarks')->nullable();
            $table->string('tier_range')->nullable(); // 82 - 90
            $table->string('deck_range')->nullable(); // 02 - 14
            $table->longText('available_stowages')->nullable();
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
