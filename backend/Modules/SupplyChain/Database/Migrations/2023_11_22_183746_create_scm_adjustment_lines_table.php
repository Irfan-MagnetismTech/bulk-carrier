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
        Schema::create('scm_adjustment_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scm_adjustment_id')->nullable();
            $table->foreign('scm_adjustment_id')->references('id')->on('scm_adjustments')->onDelete('cascade');
            $table->unsignedBigInteger('scm_material_id')->nullable();
            $table->string('unit')->nullable();
            $table->string('ajt_composite_key')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('rate', 10, 2)->nullable();
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
        Schema::dropIfExists('scm_adjustment_lines');
    }
};
