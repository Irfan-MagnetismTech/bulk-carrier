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
        Schema::create('scm_mmr_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_mmr_id')->constrained('scm_mmrs');
            $table->foreignId('scm_material_id')->constrained('scm_materials');
            $table->string('specification')->nullable();
            $table->string('unit')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('mmr_composite_key')->nullable();
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
        Schema::dropIfExists('scm_mmr_lines');
    }
};
