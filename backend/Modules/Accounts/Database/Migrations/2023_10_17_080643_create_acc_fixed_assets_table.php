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
        Schema::create('acc_fixed_assets', function (Blueprint $table) {
            $table->id();
			$table->integer('acc_cost_center_id');
			$table->unsignedBigInteger('department_id')->nullable(); // field might change or remove.
			$table->unsignedBigInteger('acc_account_id');
			$table->unsignedBigInteger('cr_account_id')->nullable(); // field might change or remove.
			$table->unsignedBigInteger('acc_material_id');

			$table->date('received_date');
			$table->string('asset_name');
			$table->string('asset_type');

			$table->string('tag')->nullable();
			$table->string('mrr_no')->nullable();
			$table->string('bill_no')->nullable();
			$table->string('brand')->nullable();
			$table->string('location')->nullable();
			$table->string('model')->nullable();
			$table->string('serial')->nullable();

			$table->float('price');
			$table->float('acquisition_cost');
			$table->integer('useful_life'); // in years             
			$table->date('acquisition_date'); // use from date
			$table->decimal('percentage');

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
        Schema::dropIfExists('acc_fixed_assets');
    }
};
