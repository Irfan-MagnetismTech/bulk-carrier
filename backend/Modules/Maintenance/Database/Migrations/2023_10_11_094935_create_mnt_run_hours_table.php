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

        Schema::create('mnt_run_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->unsignedBigInteger('mnt_item_id');
            $table->foreign('mnt_item_id')->references('id')->on('mnt_items');
            $table->integer('previous_run_hour')->nullable();
            $table->integer('present_run_hour')->nullable();
            $table->date('updated_on')->nullable();
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
        Schema::dropIfExists('mnt_run_hours');
    }
};
