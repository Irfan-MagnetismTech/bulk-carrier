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
        Schema::create('scm_po_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_po_id')->constrained('scm_pos')->cascadeOnDelete();
            $table->unsignedBigInteger('scm_pr_id')->nullable();
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
        Schema::dropIfExists('scm_po_lines');
    }
};
