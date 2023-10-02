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
        Schema::create('crw_crew_checklist_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_crew_checklist_id')->constrained('crw_crew_checklists', 'id')->cascadeOnDelete();
			$table->string('item_name');
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
        Schema::dropIfExists('crw_crew_checklist_lines');
    }
};
