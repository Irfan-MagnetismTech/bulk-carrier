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
        Schema::create('appraisal_form_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('appraisal_form_id')->constrained('appraisal_forms', 'id')->cascadeOnDelete();
            $table->string('section_no');
            $table->string('section_name');
            $table->tinyInteger('is_tabular')->default(1);
            $table->string('line_composite');
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
        Schema::dropIfExists('appraisal_form_lines');
    }
};
