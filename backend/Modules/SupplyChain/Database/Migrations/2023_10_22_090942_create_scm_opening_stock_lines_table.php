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
        Schema::create('scm_opening_stock_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_opening_stock_id')->constrained('scm_opening_stocks')->nullable();
            $table->foreignId('scm_material_id')->constrained('scm_material')->nullable();
            $table->decimal('rate')->nullable();
            $table->bigInteger('quantity')->nullable();
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
        Schema::dropIfExists('scm_opening_stock_lines');
    }
};
