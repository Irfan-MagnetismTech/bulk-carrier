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
        Schema::create('crw_crew_experiences', function (Blueprint $table) {
            $table->id();            
            $table->string('employer_name'); // organization name 
            $table->date('from_date');
            $table->date('till_date');
            $table->string('last_designation');
            $table->text('reason_for_leave');
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
        Schema::dropIfExists('crw_crew_experiences');
    }
};
