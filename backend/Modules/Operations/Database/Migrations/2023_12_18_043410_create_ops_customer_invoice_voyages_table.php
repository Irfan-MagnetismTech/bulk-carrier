<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Database\Migrations\Migration;
use Modules\Operations\Entities\OpsCargoType;
use Modules\Operations\Entities\OpsContractTariff;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ops_customer_invoice_voyages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_customer_invoice_id')->constrained('ops_customer_invoices')->onDelete('cascade');
            $table->foreignIdFor(OpsVoyage::class);
            $table->foreignIdFor(OpsVessel::class);
            $table->foreignIdFor(OpsCargoType::class);
            $table->float('total_amount_bdt', 20, 2)->nullable();
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
        Schema::dropIfExists('ops_customer_invoice_voyages');
    }
};
