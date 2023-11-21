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
        Schema::create('mnt_job_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_job_id');
            $table->foreign('mnt_job_id')->references('id')->on('mnt_jobs');
            $table->text('job_description');
            $table->integer('cycle', 255)->nullable();
            $table->string('cycle_unit', 255)->nullable();
            $table->date('last_done')->nullable();
            $table->integer('min_limit', 255)->nullable();
            $table->integer('previous_run_hour')->nullable()->default('0');
            $table->text('remarks')->nullable();
            $table->string('status', 255)->nullable();
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
        Schema::dropIfExists('mnt_job_lines');
    }
};
