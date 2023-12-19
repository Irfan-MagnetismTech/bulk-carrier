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
        Schema::create('ops_customer_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_customer_id')->constrained('ops_customers')->onDelete('cascade');
            $table->float('total_amount_bdt', 20, 4)->nullable();
            $table->float('discounted_amount', 20, 4)->nullable();
            $table->float('others_billable_amount', 20, 4)->nullable();
            $table->float('service_fee_deduction_amount', 20, 4)->nullable();
            $table->float('grand_total', 20, 4)->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL', 'ALL']);
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
        Schema::dropIfExists('ops_customer_invoices');
    }
};
