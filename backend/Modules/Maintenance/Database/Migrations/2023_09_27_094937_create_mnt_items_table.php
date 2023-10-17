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

        Schema::create('mnt_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnt_ship_department_id');
            $table->foreign('mnt_ship_department_id')->references('id')->on('mnt_ship_departments');
            $table->unsignedBigInteger('mnt_item_group_id');
            $table->foreign('mnt_item_group_id')->references('id')->on('mnt_item_groups');
            $table->string('name', 255);
            $table->string('item_code', 255);
            $table->text('description')->nullable();
            $table->enum('business_unit',['TSLL','PSML']);
            $table->integer('has_run_hour');
            $table->integer('present_run_hour');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnt_items');
    }
};
