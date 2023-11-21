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
        Schema::create('acc_fixed_asset_costs', function (Blueprint $table) {
            $table->id();
			$table->foreignId('acc_fixed_asset_id')->constrained('acc_fixed_assets', 'id')->cascadeOnDelete();
			$table->string('particular');
			$table->string('remarks')->nullable();
			$table->float('amount');
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
        Schema::dropIfExists('acc_fixed_asset_costs');
    }
};
