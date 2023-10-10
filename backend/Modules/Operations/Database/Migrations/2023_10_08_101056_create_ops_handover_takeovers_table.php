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
        Schema::create('ops_handover_takeovers', function (Blueprint $table) {
            $table->id();
            $table->enum('note_type', ['Delivery', 'Re-delivery']);
            $table->dateTime('effective_date');
            $table->float('exchange_rate');
            $table->string('currency');
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels');
            $table->foreignId('ops_charterer_profile_id')->constrained('ops_charterer_profiles');
            $table->string('business_unit')->nullable();
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
        Schema::dropIfExists('ops_handover_takeovers');
    }
};
