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
        Schema::create('crw_crew_ranks', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_crew_id');
			$table->unsignedBigInteger('crw_rank_id');
			$table->string('rank_name');
			$table->date('effective_date');
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
        Schema::dropIfExists('crw_crew_ranks');
    }
};
