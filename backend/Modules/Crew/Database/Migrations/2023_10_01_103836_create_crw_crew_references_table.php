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
        Schema::create('crw_crew_references', function (Blueprint $table) {
            $table->id();            
            $table->string('name');
            $table->string('organization');
            $table->string('designation');
            $table->text('address');
            $table->string('contact_personal');
            $table->string('contact_office')->nullable();
            $table->string('email')->nullable();
            $table->string('relation');
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
        Schema::dropIfExists('crw_crew_references');
    }
};
