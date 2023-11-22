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
        Schema::create('crw_crew_documents', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_crew_profile_id');
			$table->string('document_name'); // document name
			$table->string('issuing_authority');
			$table->string('validity_period'); //permanent / 5 Years
			$table->float('validity_period_in_month'); //60
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
        Schema::dropIfExists('crw_crew_documents');
    }
};
