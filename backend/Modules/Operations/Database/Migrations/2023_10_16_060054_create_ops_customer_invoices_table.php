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
            $table->float('sub_total', 20, 2)->nullable();
            $table->float('discount', 20, 2)->nullable();
            $table->float('grand_total', 20, 2)->nullable();
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
