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
        Schema::create('acc_ledger_entries', function (Blueprint $table) {
            $table->id();
			$table->foreignId('acc_transaction_id')->constrained('transactions', 'id')->cascadeOnDelete();
			$table->unsignedBigInteger('acc_balance_and_income_line_id');
			$table->unsignedBigInteger('acc_cost_center_id')->nullable();
			$table->unsignedBigInteger('acc_account_id');
			$table->decimal('dr_amount', 16, 2)->nullable();
			$table->decimal('cr_amount', 16, 2)->nullable();
			$table->string('ref_bill')->nullable();
			$table->string('bill_status')->nullable();
			$table->text('purpose')->nullable();
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
        Schema::dropIfExists('acc_ledger_entries');
    }
};
