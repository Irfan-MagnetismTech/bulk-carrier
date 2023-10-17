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
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');           
            $table->foreignId('ops_maritime_certification_id')->constrained('ops_maritime_certifications')->onDelete('cascade');
            $table->date('issue_date');
            $table->date('expire_date');
            $table->text('attachment')->nullable();
            $table->string('status')->nullable();
            $table->string('reference_number');
            $table->enum('business_unit', ['PSML', 'TSLL','BOTH'])->nullable();
            $table->bigInteger('created_by')->nullable();
            
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
