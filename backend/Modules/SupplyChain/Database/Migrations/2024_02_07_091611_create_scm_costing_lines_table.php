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
        Schema::create('scm_costing_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_costing_id')->constrained('scm_costings')->cascadeOnDelete();
            $table->unsignedBigInteger('scm_lc_record_id')->nullable();
            $table->string('particulars');
            $table->double('exchange_rate', 15, 2)->default(0);
            $table->double('usd_amount', 15, 2)->default(0);
            $table->double('bdt_amount', 15, 2)->default(0);
            $table->string('type');
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
        Schema::dropIfExists('scm_costing_lines');
    }
};
