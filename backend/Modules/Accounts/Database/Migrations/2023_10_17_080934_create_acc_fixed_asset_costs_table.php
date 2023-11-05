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
			$table->foreignId('fixed_asset_id')->constrained('fixed_assets', 'id')->cascadeOnDelete();
			$table->string('particular')->nullable();
			$table->date('amount')->nullable();
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
