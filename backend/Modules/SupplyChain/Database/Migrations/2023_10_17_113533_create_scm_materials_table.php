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
        Schema::create('scm_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_material_category_id')->constrained('scm_material_categories');
            $table->string('name')->unique();
            $table->string('material_code')->unique()->nullable();
            $table->string('hs_code')->nullable();
            $table->string('store_category')->nullable();
            $table->string('unit')->nullable();
            $table->integer('minimum_stcok')->nullable();
            $table->string('description')->nullable();
            $table->string('sample_photo')->nullable();
            $table->bigInteger('account_id')->nullable();
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
        Schema::dropIfExists('scm_materials');
    }
};
