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
        Schema::create('crw_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crw_crew_id');
			$table->string('bank_name');
			$table->string('account_holder');
			$table->text('address');
			$table->string('account_no');
			$table->string('currency');
			$table->string('swift_code')->nullable();
			$table->string('benificiary_name');
			$table->string('benificiary_attachment');
			$table->tinyInteger('is_active');
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
        Schema::dropIfExists('crw_bank_accounts');
    }
};
