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
        Schema::create('scm_mrr_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_mrr_line_id')->constrained('scm_mrr_lines');$table->unsignedBigInteger('scm_material_id');
            $table->string('unit')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->bigInteger('tolerence_quantity')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('rate')->nullable();
            $table->string('po_composite_key')->nullable();
            $table->string('pr_composite_key')->nullable();
            $table->string('mrr_composite_key')->nullable();
            $table->decimal('net_rate', 10, 2)->nullable();
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
        Schema::dropIfExists('scm_mrr_items');
    }
};
