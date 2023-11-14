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
        Schema::create('crw_crew_educations', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_crew_profile_id')->constrained('crw_crew_profiles', 'id')->cascadeOnDelete();
            $table->string('exam_title');
            $table->string('major');
            $table->string('institute');
            $table->string('result');
            $table->year('passing_year');
            $table->string('duration')->nullable();
            $table->string('achievement')->nullable();
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
        Schema::dropIfExists('crw_crew_educations');
    }
};
