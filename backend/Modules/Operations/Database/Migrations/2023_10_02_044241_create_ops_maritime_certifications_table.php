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
        Schema::create('ops_maritime_certifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('validity');
            $table->string('authority')->nullable();
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
        Schema::dropIfExists('ops_maritime_certifications');
    }
};
