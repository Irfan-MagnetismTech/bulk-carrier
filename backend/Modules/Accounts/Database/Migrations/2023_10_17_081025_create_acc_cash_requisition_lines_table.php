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
        Schema::create('acc_cash_requisition_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('acc_cash_requisition_id')->constrained('acc_cash_requisitions', 'id')->cascadeOnDelete();
			$table->string('particular');
            $table->decimal('amount', 10, 2)->nullable();
			$table->text('remarks')->nullable();
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
        Schema::dropIfExists('acc_cash_requisition_lines');
    }
};
