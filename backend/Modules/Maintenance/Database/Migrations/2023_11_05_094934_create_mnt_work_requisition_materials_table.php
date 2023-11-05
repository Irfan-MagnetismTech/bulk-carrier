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

        Schema::create('mnt_work_requisition_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_work_requisition_id');
            $table->foreign('mnt_work_requisition_id')->references('id')->on('mnt_work_requisitions');
            $table->string('material_name');
            $table->text('specification')->nullable();
            $table->string('unit', 255)->nullable();
            $table->integer('quantity');
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
        Schema::dropIfExists('mnt_work_requisition_materials');
    }
};
