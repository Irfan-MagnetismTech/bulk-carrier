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

        Schema::create('mnt_critical_item_sps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_critical_vessel_item_id');
            $table->foreign('mnt_critical_vessel_item_id')->references('id')->on('mnt_critical_vessel_items');
            $table->string('sp_name', 255);
            $table->string('unit')->nullable();
            $table->integer('min_rob');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_critical_item_sps');
    }
};
