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
        Schema::create('ops_cargo_tariff_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_cargo_tariff_id');
            $table->string('particular');
            $table->string('unit');
            $table->float('jan');
            $table->float('feb');
            $table->float('mar');
            $table->float('apr');
            $table->float('may');
            $table->float('jun');
            $table->float('jul');
            $table->float('aug');
            $table->float('sep');
            $table->float('oct');
            $table->float('nov');
            $table->float('dec');
            $table->timestamps();
            $table->foreign('ops_cargo_tariff_id')->references('id')->on('ops_cargo_tariffs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ops_cargo_tariff_lines');
    }
};
