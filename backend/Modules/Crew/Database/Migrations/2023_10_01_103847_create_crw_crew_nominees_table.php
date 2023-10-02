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
        Schema::create('crw_crew_nominees', function (Blueprint $table) {
            $table->id();            
            $table->string('name');
            $table->string('profession');
            $table->string('address');
            $table->string('relationship');
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->string('is_relative');
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
        Schema::dropIfExists('crw_crew_nominees');
    }
};
