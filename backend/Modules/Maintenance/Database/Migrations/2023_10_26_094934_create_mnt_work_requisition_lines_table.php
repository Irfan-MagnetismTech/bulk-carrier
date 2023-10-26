<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('mnt_work_requisition_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_work_requisition_item_id');
            $table->foreign('mnt_work_requisition_item_id')->references('id')->on('mnt_work_requisitions');
            $table->unsignedBigInteger('mnt_job_id');
            $table->foreign('mnt_job_id')->references('id')->on('mnt_jobs');
            $table->date('last_done')->nullable();
            $table->integer('running_hour')->nullable();
            $table->integer('status')->default('0')->comment('0=Pending, 1=WIP, 2=Done');
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_work_requisition_lines');
    }
};
