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
            $table->foreignId('scm_material_id')->constrained('scm_materials');
            $table->string('unit')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->date('required_date')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('rate')->nullable();
            $table->decimal('total_price')->nullable();
            $table->decimal('net_rate')->nullable()->comment('This cost is for net cost, i.e; unit_price + other_cost_per_unit')->nullable();
            $table->string('po_composite_key')->nullable();
            $table->string('pr_composite_key')->nullable();
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
