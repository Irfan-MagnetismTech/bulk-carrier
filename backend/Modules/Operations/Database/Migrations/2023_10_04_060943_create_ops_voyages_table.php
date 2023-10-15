<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Operations\Entities\OPSCustomer;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ops_voyages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_customer_id')->constrained();
            $table->foreignId('ops_vessel_id')->constrained();
            $table->bigInteger('mother_vessel_id')->nullable();
            $table->string('voyage_no');
            $table->string('route');
            $table->foreignId('ops_cargo_type_id')->constrained();
            $table->string('load_port_distance');
            $table->dateTime('sail_date');
            $table->dateTime('transit_date');
            $table->text('remarks');
            $table->string('business_unit');
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
        Schema::dropIfExists('ops_voyages');
    }
};
