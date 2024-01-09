<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Modules\SupplyChain\Entities\ScmMaterial;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('ops_vessel_bunkers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OpsVessel::class);
            $table->foreignIdFor(OpsVoyage::class)->nullable();
            $table->enum('type', ['Stock In','Stock Out'])->nullable();
            $table->enum('usage_type', ['Idle', 'Voyage Wise'])->nullable();
            $table->string('currency')->nullable();
            $table->float('exchange_rate_bdt', 20, 2)->nullaSSble();
            $table->float('exchange_rate_usd', 20, 2)->nullable();
            $table->float('total_amount_bdt', 20, 2)->nullable();
            $table->float('total_amount_usd', 20, 2)->nullable();
            $table->date('date');
            $table->date('from_date')->nullable();
            $table->date('till_date')->nullable();
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
        Schema::dropIfExists('ops_vessel_bunkers');
    }
};
