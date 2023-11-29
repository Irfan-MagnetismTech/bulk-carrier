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
        Schema::create('acc_depreciation_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('acc_depreciation_id')->constrained('acc_depreciations', 'id')->cascadeOnDelete();
			$table->unsignedBigInteger('acc_fixed_asset_id');
            $table->float('depreciation_rate'); // in percentage
			$table->decimal('amount', 16, 2);
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
        Schema::dropIfExists('acc_depreciation_lines');
    }
};
