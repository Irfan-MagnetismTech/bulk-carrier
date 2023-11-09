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
        Schema::create('ops_bunker_bill_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_bunker_bill_id')->constrained('ops_bunker_bills')->onDelete('cascade');
            $table->string('pr_no');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('ops_bunker_bill_lines');
    }
};
