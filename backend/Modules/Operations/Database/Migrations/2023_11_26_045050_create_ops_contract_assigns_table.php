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
        Schema::create('ops_contract_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_vessel_id')->constrained('scm_materials')->onDelete('cascade');
            $table->foreignId('ops_voyage_id')->constrained('scm_materials')->onDelete('cascade');
            $table->foreignId('ops_tariff_id')->constrained('scm_materials')->onDelete('cascade');
            $table->foreignId('ops_customer_id')->constrained('scm_materials')->onDelete('cascade');
            $table->foreignId('ops_charterer_id')->constrained('scm_materials')->onDelete('cascade');
            $table->foreignId('ops_charterer_contract_id')->constrained('scm_materials')->onDelete('cascade');
            $table->text('remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']);
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
        Schema::dropIfExists('ops_contract_assigns');
    }
};
