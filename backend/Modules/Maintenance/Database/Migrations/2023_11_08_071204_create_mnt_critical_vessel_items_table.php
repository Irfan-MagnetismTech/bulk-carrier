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

        Schema::create('mnt_critical_vessel_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ops_vessel_id');
            $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->unsignedBigInteger('mnt_critical_item_id');
            $table->foreign('mnt_critical_item_id')->references('id')->on('mnt_critical_items');
            $table->integer('is_critical')->default('0')->comment('0=No, 1=Yes');
            $table->text('notes')->nullable();
            $table->string('business_unit', 255);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_critical_vessel_items');
    }
};
