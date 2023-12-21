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
        Schema::create('ops_bunker_bill_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_bunker_bill_id')->constrained('ops_bunker_bills')->onDelete('cascade');
            $table->foreignId('ops_bunker_bill_line_id')->constrained('ops_bunker_bill_lines')->onDelete('cascade');
            $table->string('particular')->nullable();
            $table->string('requisition_material')->nullable();
            $table->float('quantity', 20, 2)->nullable();
            $table->float('rate', 20, 2)->nullable();
            $table->float('exchange_rate_bdt', 20, 2)->nullable();
            $table->float('exchange_rate_usd', 20, 2)->nullable();
            $table->float('amount', 20, 2)->nullable();
            $table->float('amount_bdt', 20, 2)->nullable();
            $table->float('amount_usd', 20, 2)->nullable();
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
        Schema::dropIfExists('ops_bunker_bill_line_items');
    }
};