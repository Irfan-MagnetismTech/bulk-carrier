<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_record_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appraisal_record_line_id')->constrained('appraisal_record_lines', 'id')->cascadeOnDelete();
            $table->string('item_composite');
            $table->text('comment');
            $table->string('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appraisal_record_line_items');
    }
};
