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
            $table->date('quotation_date')->nullable()->comment('pi received date');
            $table->date('quotation_received_date')->nullable();
            $table->string('quotation_attachment')->nullable();
            $table->string('quotation_validity')->nullable()->comment('offer validity');
            $table->string('payment_method')->comment('as payment condition => cash,credit,lc,advance')->nullable();
            $table->string('delivery_term')->nullable();
            $table->string('sourcing')->comment('existing,new')->nullable();
            $table->date('date_of_rfq')->nullable();
            $table->string('vendor_type')->comment('supplier,manufacturer,dealer')->nullable();
            $table->date('quotation_shipment_date')->nullable();
            $table->date('estimated_shipment')->nullable();
            $table->string('port_of_shipment')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('currency')->nullable();
            $table->string('mode_of_shipment')->comment('air,land,sea')->nullable();
            $table->string('terms_and_condition')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('remarks')->nullable();
            $table->string('credit_term')->nullable();
            $table->string('carring_cost_bear_by')->nullable();
            $table->string('unloading_cost_bear_by')->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('vat')->nullable();
            $table->string('ait')->nullable();
            $table->bigInteger('is_selected')->nullable();
            $table->string('technical_acceptance')->nullable();
            $table->string('stock_type')->comment('ready_stock,manufacturer')->nullable();
            $table->string('manufacturing_days')->comment('if stock type manufacturer')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('warranty')->nullable();
            $table->bigInteger('delivery_time')->nullable();
            $table->double('total_negotiated_price')->nullable();
            $table->double('total_offered_price')->nullable();
            $table->double('freight')->nullable();
            $table->double('grand_total_negotiated_price')->nullable();
            $table->double('grand_total_offered_price')->nullable();
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
