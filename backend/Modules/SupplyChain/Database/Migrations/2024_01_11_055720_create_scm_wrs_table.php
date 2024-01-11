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
        Schema::create('scm_wrs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('scm_warehouse_id')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->date('raised_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('attachment')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable();
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
        Schema::dropIfExists('scm_wrs');
    }
};
