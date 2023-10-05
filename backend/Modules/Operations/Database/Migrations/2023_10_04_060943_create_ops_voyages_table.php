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
            $table->foreignId('ops_customer_id')->constrained('ops_customers');
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels');
            $table->bigInteger('mother_vessel_id')->nullable();
            $table->foreignId('ops_cargo_type_id')->constrained('ops_cargo_types');
            $table->string('voyage_no');
            $table->string('route');
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
