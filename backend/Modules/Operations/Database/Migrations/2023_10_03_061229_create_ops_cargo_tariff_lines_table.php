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
            $table->foreignId('ops_cargo_tariff_id')->constrained('ops_cargo_tariffs')->onDelete('cascade');
            $table->string('particular');
            $table->string('unit');
            $table->float('jan')->nullable();
            $table->float('feb')->nullable();
            $table->float('mar')->nullable();
            $table->float('apr')->nullable();
            $table->float('may')->nullable();
            $table->float('jun')->nullable();
            $table->float('jul')->nullable();
            $table->float('aug')->nullable();
            $table->float('sep')->nullable();
            $table->float('oct')->nullable();
            $table->float('nov')->nullable();
            $table->float('dec')->nullable();
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
        Schema::dropIfExists('ops_cargo_tariff_lines');
    }
};
