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
        Schema::create('scm_wrr_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_wrr_id')->constrained('scm_wrrs')->nullable();
            $table->unsignedBigInteger('scm_wr_id')->nullable();
            $table->unsignedBigInteger('scm_wo_id')->nullable();
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
        Schema::dropIfExists('scm_wrr_lines');
    }
};
