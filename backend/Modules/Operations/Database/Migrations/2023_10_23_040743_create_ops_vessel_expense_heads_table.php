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
        Schema::create('ops_vessel_expense_heads', function (Blueprint $table) {
            $table->id();
            $table->string('ops_vessel_id')->nullable();
            $table->foreignId('ops_expense_head_id')->constrained('ops_expense_heads')->onDelete('cascade');
            $table->enum('business_unit', ['PSML', 'TSLL','ALL']);
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
        Schema::dropIfExists('ops_vessel_expense_heads');
    }
};
