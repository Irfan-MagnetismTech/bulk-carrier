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

        Schema::create('mnt_work_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_work_requisition_id');
            $table->foreign('mnt_work_requisition_id')->references('id')->on('mnt_work_requisitions');
            $table->unsignedBigInteger('mnt_item_id');
            $table->foreign('mnt_item_id')->references('id')->on('mnt_items');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_work_requisition_items');
    }
};
