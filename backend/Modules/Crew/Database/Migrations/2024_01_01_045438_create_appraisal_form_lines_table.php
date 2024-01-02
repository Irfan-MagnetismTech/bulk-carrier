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
            $table->string('section_name');
            $table->string('aspect');
            $table->text('description')->nullable();
            $table->enum('answer_type', ['Number', 'Boolean', 'Grade']);            
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
