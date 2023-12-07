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
        Schema::create('scm_cs_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_cs_id')->constrained('scm_cs')->onDelete('cascade');
            $table->bigInteger('scm_vendor_id');
            $table->string('quotation_ref');
            $table->date('quotation_date');
            $table->date('quotations_received_date');
            $table->string('quotation_attachment');
            $table->string('quotation_validity');
            $table->string('payment_method')->comment('cash,credit,lc,advance');
            $table->string('delivery_term');
            $table->string('sourcing')->comment('existing,new');
            $table->date('date_of_rfq');
            $table->string('vendor_type')->comment('supplier,manufacturer,dealer');
            $table->date('quotation_shipment_date');
            $table->date('estimated_shipment');
            $table->bigInteger('port_of_shipment');
            $table->bigInteger('port_of_discharge');
            $table->string('currency');
            $table->string('mode_of_shipment')->comment('air,land,sea');
            $table->string('terms_and_condition');
            $table->string('remarks');
            $table->string('credit_term');
            $table->string('carring_cost_bear_by');
            $table->string('unloading_cost_bear_by');
            $table->string('vat');
            $table->string('ait');
            $table->bigInteger('is_selected');
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
        Schema::dropIfExists('scm_cs_vendors');
    }
};
