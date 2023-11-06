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
            $table->foreign('mnt_work_requisition_item_id')->references('id')->on('mnt_work_requisition_items');
            $table->unsignedBigInteger('mnt_job_line_id');
            $table->foreign('mnt_job_line_id')->references('id')->on('mnt_job_lines');
            $table->text('job_description');
            $table->integer('cycle')->nullable();
            $table->string('cycle_unit', 255)->nullable();
            $table->date('last_done')->nullable();
            $table->integer('min_limit')->nullable();
            $table->integer('previous_run_hour')->nullable()->default('0');
            $table->integer('present_run_hour')->nullable();
            $table->integer('status')->nullable()->default('0')->comment('0=Pending, 1=WIP, 2=Done');
            $table->integer('checking')->nullable()->default('0')->comment('0=No, 1=Yes');
            $table->integer('replace')->nullable()->default('0')->comment('0=No, 1=Yes');
            $table->integer('cleaning')->nullable()->default('0')->comment('0=No, 1=Yes');
            $table->text('remarks')->nullable();
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
