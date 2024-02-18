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
        Schema::create('scm_mrr_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_mrr_id')->constrained('scm_mrrs')->nullable();
            $table->unsignedBigInteger('scm_pr_id')->nullable();
            $table->unsignedBigInteger('scm_po_id')->nullable();
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
        Schema::dropIfExists('scm_mrr_lines');
    }
};
