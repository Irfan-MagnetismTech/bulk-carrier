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
        Schema::create('scm_costing_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_costing_id')->constrained('scm_costings')->cascadeOnDelete();
            $table->unsignedBigInteger('scm_mrr_id');
            $table->integer('value');
            $table->integer('allocated_amount');
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
        Schema::dropIfExists('scm_costing_allocations');
    }
};