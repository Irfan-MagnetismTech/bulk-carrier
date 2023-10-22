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
        Schema::create('ops_expense_heads', function (Blueprint $table) {
            $table->id();
            $table->string('billing_type');
            $table->foreignId('head_id')->constrained('ops_expense_heads')->onDelete('cascade')->nullable();
            $table->string('name');
            $table->tinyInteger('is_visible_in_voyage_report?')->nullable();
            $table->string('business_unit');
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
        Schema::dropIfExists('ops_expense_heads');
    }
};
