<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Operations\Entities\OpsCargoTariff;
use Modules\Operations\Entities\OpsContractAssign;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Modules\Operations\Entities\OpsVoyageSector;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ops_contract_tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OpsVoyage::class);
            $table->foreignIdFor(OpsVessel::class);
            $table->foreignIdFor(OpsCargoTariff::class);
            $table->foreignIdFor(OpsVoyageSector::class);
            $table->foreignIdFor(OpsContractAssign::class);
            $table->string('loading_point');
            $table->string('unloading_point');
            $table->string('tariff_month');
            $table->double('quantity', 20,2);
            $table->double('total_rate', 20,2);
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
        Schema::dropIfExists('');
    }
};
