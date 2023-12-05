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

        Schema::create('mnt_critical_sp_list_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_critical_sp_list_id');
            $table->foreign('mnt_critical_sp_list_id')->references('id')->on('mnt_critical_sp_lists');
            $table->unsignedBigInteger('mnt_critical_item_sp_id');
            $table->foreign('mnt_critical_item_sp_id')->references('id')->on('mnt_critical_item_sps');
            $table->integer('mnt_critical_vessel_item_id')->nullable();
            $table->integer('min_rob');
            $table->integer('rob');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_critical_sp_list_lines');
    }
};
