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
        Schema::create('acc_accounts', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('acc_balance_and_income_line_id');
			$table->unsignedBigInteger('parent_account_id')->nullable();
			$table->string('account_name');
			$table->string('account_code');
			$table->integer('account_type'); // will come from config/account.php named account_types = ['Assets' => 1,'Liabilities'  2,'Equity' => 3,'Revenues' => 4,'Expenses' => 5]
			$table->string('accountable_type')->nullable(); // Morph = Source Model Name
			$table->integer('accountable_id')->nullable(); // Morph = Source Id
			$table->string('official_code')->nullable();
			$table->tinyInteger('is_archived')->default(0);
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
        Schema::dropIfExists('acc_accounts');
    }
};
