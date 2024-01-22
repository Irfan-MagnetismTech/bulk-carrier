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
        Schema::create('crw_rest_hour_entry_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_rest_hour_entry_id')->constrained('crw_rest_hour_entries', 'id')->cascadeOnDelete();
			$table->unsignedBigInteger('crw_crew_id');
			$table->unsignedBigInteger('crw_crew_assignment_id');
            $table->decimal('work_hours', 4, 2); 
            $table->decimal('rest_hours', 4, 2); 
            $table->decimal('overtime_hours', 4, 2); 
            $table->decimal('applicable_rest_hour_daily', 8, 2); 
            $table->decimal('applicable_rest_hour_weekly', 8, 2); 
            $table->text('comments')->nullable();
            $table->text('hourly_records');
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
        Schema::dropIfExists('crw_rest_hour_entry_lines');
    }
};
