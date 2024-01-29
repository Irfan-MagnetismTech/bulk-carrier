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
        Schema::create('scm_cs_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_cs_id')->constrained('scm_cs')->onDelete('cascade');
            $table->bigInteger('scm_material_id');
            $table->string('unit');
            $table->bigInteger('quantity');
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
        Schema::dropIfExists('scm_cs_materials');
    }
};
