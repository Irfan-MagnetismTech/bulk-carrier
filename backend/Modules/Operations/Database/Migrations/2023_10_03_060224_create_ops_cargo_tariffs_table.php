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
        Schema::create('ops_cargo_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('tariff_name')->unique();
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->foreignId('ops_cargo_type_id')->constrained('ops_cargo_types')->onDelete('cascade');
            $table->string('loading_point');
            $table->string('unloading_point');
            $table->string('currency');
            $table->enum('status',['Active','Inactive']);
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
        Schema::dropIfExists('ops_cargo_tariffs');
    }
};
