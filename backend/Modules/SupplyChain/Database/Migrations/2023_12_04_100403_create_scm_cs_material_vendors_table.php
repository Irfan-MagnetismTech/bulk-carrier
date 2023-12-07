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
            $table->bigInteger('scm_cs_material_id');
            $table->bigInteger('scm_material_id');
            $table->string('brand');
            $table->string('model');
            $table->string('origin');
            $table->string('stock_type')->comment('ready_stock,manufacturer');
            $table->string('manufacturing_days')->comment('if stock type manufacturer');
            $table->decimal('offered_price');
            $table->decimal('negotiated_price')->comment('for local it is `Price`');
            $table->bigInteger('quantity');
            $table->decimal('amount');
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
