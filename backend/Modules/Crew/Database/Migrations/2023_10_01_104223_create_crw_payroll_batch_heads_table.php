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
        Schema::create('crw_payroll_batch_heads', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('payroll_batch_id');
            $table->string('head_type');
            $table->string('head_name');
            $table->string('unit');
            $table->string('based_on');
            $table->decimal('amount');
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
        Schema::dropIfExists('crw_payroll_batch_heads');
    }
};
