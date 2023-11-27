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
        Schema::create('acc_cash_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acc_cost_center_id');
            $table->date('applied_date');
            $table->unsignedBigInteger('requisitor_id');
            $table->unsignedBigInteger('scm_pr_id')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->text('purpose');
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
        Schema::dropIfExists('acc_cash_requisitions');
    }
};
