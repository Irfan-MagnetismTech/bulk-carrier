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
            $table->foreignId('crw_payroll_batch_id')->constrained('crw_payroll_batches', 'id')->cascadeOnDelete();
            $table->unsignedBigInteger('crw_payroll_batch_head_id');
            $table->unsignedBigInteger('crw_crew_id');
            $table->string('head_type');
            $table->string('amount', 16, 2);            
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
