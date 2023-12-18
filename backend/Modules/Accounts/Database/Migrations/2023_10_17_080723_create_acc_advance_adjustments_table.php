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
        Schema::create('acc_advance_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acc_cost_center_id');
			$table->unsignedBigInteger('acc_cash_requisition_id');
			$table->date('adjustment_date');
			$table->decimal('adjustment_amount');
			$table->enum('business_unit', ['PSML', 'TSLL']);
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
        Schema::dropIfExists('acc_advance_adjustments');
    }
};
