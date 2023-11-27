<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_fixed_assets', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('acc_cost_center_id');
            $table->unsignedBigInteger('scm_mrr_id');
            $table->unsignedBigInteger('scm_material_id');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial')->nullable();
            $table->unsignedBigInteger('acc_parent_account_id'); // Asset Category (In Front End) == Fixed Assets at Cost (id:3)
            $table->unsignedBigInteger('acc_account_id');
            $table->string('asset_tag')->nullable();
            $table->float('useful_life'); // in years
            $table->float('depreciation_rate'); // in percentage
            $table->string('location')->nullable();
            $table->date('acquisition_date'); // use from date
            $table->float('acquisition_cost');
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
