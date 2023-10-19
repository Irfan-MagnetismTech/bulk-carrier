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
        Schema::create('ops_bunker_bills', function (Blueprint $table) {
            $table->id();            
            $table->date('date');
            $table->bigInteger('scm_vendor_id')->nullable();
            // $table->foreignId('scm_vendor_id')->constrained('scm_vendors')->onDelete('cascade');
            $table->string('c');
            $table->text('remarks');
            $table->text('attachment');
            $table->string('smr_file_path');
            $table->float('sub_total');
            $table->float('discount');
            $table->float('grand_total')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']);
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
        Schema::dropIfExists('ops_bunker_bills');
    }
};
