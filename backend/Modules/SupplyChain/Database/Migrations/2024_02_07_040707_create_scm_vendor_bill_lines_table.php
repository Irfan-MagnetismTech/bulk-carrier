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
        Schema::create('scm_vendor_bill_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_vendor_bill_id')->nullable();
            $table->bigInteger('scm_mrr_id')->nullable();
            $table->bigInteger('scm_po_id')->nullable();
            $table->decimal('amount', 20, 2)->nullable();
            $table->decimal('amount_bdt', 20, 2)->nullable();
            $table->decimal('amount_usd', 20, 2)->nullable();
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
        Schema::dropIfExists('scm_vendor_bill_lines');
    }
};
