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
        Schema::create('crw_agencies', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('legal_name');
			$table->string('tax_identification')->nullable();
			$table->string('business_license_no')->nullable();
			$table->string('company_reg_no')->nullable();
			$table->string('address');
			$table->string('phone');
			$table->string('email');
			$table->string('website')->nullable();
			$table->string('logo')->nullable();
			$table->string('country')->nullable();
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
        Schema::dropIfExists('crw_agencies');
    }
};
