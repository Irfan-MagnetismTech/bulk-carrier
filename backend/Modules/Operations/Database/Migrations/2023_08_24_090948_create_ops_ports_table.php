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
        Schema::create('ops_ports', function (Blueprint $table) {
            $table->id();
            $table->string('code',10);
            $table->string('name');
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('ops_ports');
    }
};
