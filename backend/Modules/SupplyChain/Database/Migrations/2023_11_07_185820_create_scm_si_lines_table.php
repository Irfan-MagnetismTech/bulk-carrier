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
        Schema::create('scm_si_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_si_id')->constrained('scm_sis')->onDelete('cascade');
            $table->foreignId('scm_material_id')->constrained('scm_materials');
            $table->string('unit')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('sr_composite_key')->nullable();
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
        Schema::dropIfExists('scm_si_lines');
    }
};
