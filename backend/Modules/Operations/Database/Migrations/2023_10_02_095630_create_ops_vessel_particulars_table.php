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
        Schema::create('ops_vessel_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_vessel_id')->constrained('ops_vessels')->onDelete('cascade');
            $table->text('attachment');
            $table->string('class_no');
            $table->string('loa');
            $table->string('depth');
            $table->string('ecdis_type');
            $table->string('maker_ssas');
            $table->string('engine_type');
            $table->string('bhp');
            $table->string('email');
            $table->string('lbc');
            $table->enum('business_unit', ['PSML', 'TSLL','BOTH'])->nullable();
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
        Schema::dropIfExists('ops_vessel_particulars');
    }
};
