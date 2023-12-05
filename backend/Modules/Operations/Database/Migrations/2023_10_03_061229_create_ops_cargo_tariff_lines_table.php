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
            $table->float('jan', 20, 2)->nullable();
            $table->float('feb', 20, 2)->nullable();
            $table->float('mar', 20, 2)->nullable();
            $table->float('apr', 20, 2)->nullable();
            $table->float('may', 20, 2)->nullable();
            $table->float('jun', 20, 2)->nullable();
            $table->float('jul', 20, 2)->nullable();
            $table->float('aug', 20, 2)->nullable();
            $table->float('sep', 20, 2)->nullable();
            $table->float('oct', 20, 2)->nullable();
            $table->float('nov', 20, 2)->nullable();
            $table->float('dec', 20, 2)->nullable();
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
