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
        Schema::create('scm_cs_material_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_cs_id')->constrained('scm_cs')->onDelete('cascade');
            $table->bigInteger('scm_cs_vendor_id');
            $table->bigInteger('scm_vendor_id');
            $table->bigInteger('scm_pr_id');
            $table->bigInteger('scm_cs_material_id');
            $table->bigInteger('scm_material_id');
            $table->string('unit')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('origin')->nullable();
            $table->string('stock_type')->comment('ready_stock,manufacturer')->nullable();
            $table->string('manufacturing_days')->comment('if stock type manufacturer')->nullable();
            $table->decimal('offered_price')->nullable();
            $table->decimal('negotiated_price')->comment('for local it is `Price`')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal('offer_amount')->nullable();
            $table->string('warranty_period')->nullable();
            $table->string('installation_and_commission')->nullable();
            $table->string('certification')->nullable();
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
        Schema::dropIfExists('scm_cs_material_vendors');
    }
};
