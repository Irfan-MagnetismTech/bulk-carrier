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
        Schema::create('scm_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->string('vendor_type')->nullable()->comment('local, foreign');
            $table->string('account_ref_no')->nullable();
            $table->string('product_source_type')->nullable()->comment('manufacturer, dealer, supplier');
            $table->string('product_type')->nullable()->comment('lube oil, bunker, provision, spare, service');
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
        Schema::dropIfExists('scm_vendors');
    }
};
