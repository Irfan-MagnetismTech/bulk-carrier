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
        Schema::create('crw_agency_contact_persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crw_agency_id');
            $table->foreign('crw_agency_id', 'fk_crw_agency_contact_person_id')->references('id')->on('crw_agencies')->cascadeOnDelete();
			$table->string('name');
			$table->string('contact_no');
			$table->string('email')->nullable();
			$table->string('position')->nullable();
			$table->string('purpose')->nullable();
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
        Schema::dropIfExists('crw_agency_contact_persons');
    }
};
