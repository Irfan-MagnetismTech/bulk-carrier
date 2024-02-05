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
        Schema::create('scm_wrr_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_wrr_id')->constrained('scm_wrrs');
            $table->unsignedBigInteger('scm_wrr_line_id')->nullable();
            $table->unsignedBigInteger('scm_service_id');
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('rate')->nullable();
            $table->string('wo_composite_key')->nullable();
            $table->string('wr_composite_key')->nullable();
            $table->decimal('net_rate', 10, 2)->nullable();
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
        Schema::dropIfExists('scm_wrr_items');
    }
};
