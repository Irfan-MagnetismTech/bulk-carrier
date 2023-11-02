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
        Schema::create('scm_lc_records', function (Blueprint $table) {
            $table->id();
            $table->string('lc_no')->nullable();
            $table->date('lc_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->decimal('weight')->nullable();
            $table->bigInteger('no_of_packet')->nullable();
            $table->foreignId('scm_po_id')->constrained('scm_pos')->nullable();
            $table->decimal('invoice_value')->nullable();
            $table->decimal('assessment_value')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('issue_bank_id')->nullable();
            $table->bigInteger('advising_bank_id')->nullable();
            $table->bigInteger('discounting_bank_id')->nullable();
            $table->bigInteger('beneficiary_bank_id')->nullable();
            $table->string('type')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->decimal('cfr_value')->nullable();
            $table->decimal('lc_margin')->nullable();
            $table->decimal('insurance_payment')->nullable();
            $table->decimal('total_cost')->nullable();
            $table->decimal('cfr_margin')->nullable();
            $table->decimal('exchange_rate')->nullable();
            $table->decimal('market_rate')->nullable();
            $table->bigInteger('business_unit')->nullable();
            $table->string('attachment')->nullable();
            $table->bigInteger('created_by')->comment('user_id')->nullable();
            $table->bigInteger('scm_vendor_id')->comment('Party Name')->nullable();
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
        Schema::dropIfExists('scm_lc_records');
    }
};
