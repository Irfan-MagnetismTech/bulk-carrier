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
        Schema::create('crw_crew_requisition_lines', function (Blueprint $table) {
            $table->id();            
			$table->foreignId('crw_crew_requisition_id')->constrained('crw_crew_requisitions', 'id')->cascadeOnDelete();
			$table->unsignedInteger('crw_rank_id');
			$table->integer('required_manpower');
			$table->text('remarks')->nullable();
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
        Schema::dropIfExists('crw_crew_requisition_lines');
    }
};
