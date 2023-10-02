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
        Schema::create('ops_vessel_certificates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ops_vessel_id');
            $table->bigInteger('ops_maritime_certificate_id');
            $table->date('issue_date');
            $table->date('expire_date');
            $table->text('attachment')->nullable();
            $table->string('status')->nullable();
            $table->string('reference_number');
            $table->bigInteger('created_by')->nullable();
            
            // $table->foreign('ops_vessel_id')->references('id')->on('ops_vessels');
            $table->foreign('ops_maritime_certificate_id')->references('id')->on('ops_maritime_certifications')->name('maritime_certificate_id');
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
        Schema::dropIfExists('ops_vessel_certificates');
    }
};
