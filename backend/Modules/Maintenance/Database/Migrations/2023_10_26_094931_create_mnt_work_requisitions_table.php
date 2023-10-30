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

        Schema::create('mnt_work_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 255)->nullable();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->string('assigned_to', 255)->nullable();
            $table->string('responsible_person')->nullable();
            $table->unsignedBigInteger('scm_vendor_id')->nullable();
            $table->foreign('scm_vendor_id')->references('id')->on('scm_vendors');
            $table->enum('maintenance_type', ['Schedule','Breakdown','Dry Dock']);
            $table->date('requisition_date');
            $table->string('priority', 255)->nullable();
            $table->date('est_start_date')->nullable();
            $table->date('est_completion_date')->nullable();
            $table->date('act_start_date')->nullable();
            $table->date('act_completion_date')->nullable();
            $table->integer('status')->default('0')->comment('0=Pending, 1=WIP, 2=Done');
            $table->enum('business_unit',['TSLL','PSML']);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_work_requisitions');
    }
};
