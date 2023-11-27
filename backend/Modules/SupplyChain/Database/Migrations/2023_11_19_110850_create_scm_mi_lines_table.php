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
        Schema::create('scm_mi_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scm_mi_id')->nullable();
            $table->foreign('scm_mi_id')->references('id')->on('scm_mis')->onDelete('cascade');
            $table->unsignedBigInteger('scm_material_id')->nullable();
            $table->foreign('scm_material_id')->references('id')->on('scm_materials')->onDelete('cascade');
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->string('mmr_composite_key')->nullable();
            $table->string('mi_composite_key')->nullable();
            $table->string('mo_composite_key')->nullable();
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
        Schema::dropIfExists('scm_mi_lines');
    }
};
