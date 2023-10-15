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
        Schema::create('crw_crews', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_crew_profile_id');
			$table->unsignedBigInteger('crw_rank_id');
			$table->string('name');
			$table->string('contact');
			$table->string('email')->nullable();
			$table->enum('business_unit', ['PSML', 'TSLL']);
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
        Schema::dropIfExists('crw_crews');
    }
};
