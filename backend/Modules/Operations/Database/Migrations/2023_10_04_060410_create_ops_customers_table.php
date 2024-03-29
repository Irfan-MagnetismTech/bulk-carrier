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
        Schema::create('ops_customers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('legal_name');
            $table->string('name');
            $table->string('postal_address');
            $table->string('city');
            $table->string('post_code')->nullable();
            $table->string('country');
            $table->string('tax_id')->nullable();
            $table->string('business_license_no')->nullable();
            $table->string('bin_gst_sst_type')->nullable();
            $table->string('bin_gst_sst_no')->nullable();
            $table->string('phone');
            $table->string('company_reg_no')->nullable();
            $table->string('email_general')->nullable();
            $table->string('email_agreement')->nullable();
            $table->string('email_invoice')->nullable();
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
        Schema::dropIfExists('ops_customers');
    }
};
