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
        Schema::create('scm_sir_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_sir_id');
            $table->foreignId('scm_material_id')->constrained('scm_materials');
            $table->foreignId('scm_sr_id')->constrained('scm_srs');
            $table->string('unit');
            $table->bigInteger('quantity');
            $table->string('notes');
            $table->string('si_composite_key');
            $table->string('sr_composite_key');
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
        Schema::dropIfExists('scm_sir_lines');
    }
};
