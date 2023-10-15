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
        Schema::create('crw_attendance_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_attendance_id')->constrained('crw_attendances', 'id')->cascadeOnDelete();
			$table->unsignedBigInteger('crw_crew_id');
			$table->unsignedBigInteger('crw_crew_assignment_id');
			$table->string('attendance_line_composite');
			$table->integer('present_days');
			$table->integer('absent_days');
			$table->integer('payable_days');
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
        Schema::dropIfExists('crw_attendance_lines');
    }
};
