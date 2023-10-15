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
        Schema::create('crw_payroll_batch_head_lines', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('payroll_batch_line_id');
            $table->unsignedBigInteger('payroll_batch_head_id');
            $table->unsignedBigInteger('crew_id');
            $table->string('particular');
            $table->string('amount');            
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
        Schema::dropIfExists('crw_payroll_batch_head_lines');
    }
};
