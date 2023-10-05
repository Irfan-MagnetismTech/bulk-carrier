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

        Schema::create('mnt_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->integer('mnt_ship_department_id');
            $table->foreign('mnt_ship_department_id')->references('id')->on('mnt_ship_departments');
            $table->unsignedBigInteger('mnt_item_id', 255);
            $table->foreign('mnt_item_id')->references('id')->on('mnt_items');
            $table->text('job_description')->nullable();
            $table->string('cycle', 255)->nullable();
            $table->string('cycle_unit', 255)->nullable();
            $table->timestamp('last_done')->nullable();
            $table->string('min_limit', 255)->nullable();
            $table->text('next_due')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status', 255)->nullable();
            $table->enum('business_unit',['TSLL','PSML'])->default('TSLL');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_jobs');
    }
};
