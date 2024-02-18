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
            $table->foreignId('crw_payroll_batch_id')->constrained('crw_payroll_batches', 'id')->cascadeOnDelete();            
            $table->enum('head_type', ['addition', 'deduction']);
            $table->string('head_name');
            // $table->string('unit');
            // $table->string('based_on');
            $table->decimal('amount', 16, 2);
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
