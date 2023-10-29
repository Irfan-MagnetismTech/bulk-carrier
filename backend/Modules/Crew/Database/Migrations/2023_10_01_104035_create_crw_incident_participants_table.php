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
        Schema::create('crw_incident_participants', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_incident_id')->constrained('crw_incidents', 'id')->cascadeOnDelete();
			$table->unsignedBigInteger('crw_crew_id');
			$table->string('injury_status');
			$table->text('notes')->nullable();
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
        Schema::dropIfExists('crw_incident_participants');
    }
};
