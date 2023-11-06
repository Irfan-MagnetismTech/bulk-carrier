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
        Schema::create('scm_pr_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_pr_id')->constrained('scm_prs')->onDelete('cascade');
            $table->foreignId('scm_material_id')->constrained('scm_materials');
            $table->string('unit')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->string('sample_file')->nullable();
            $table->string('drawing_no')->nullable();
            $table->string('part_no')->nullable();
            $table->string('specification')->nullable();
            $table->decimal('quantity')->nullable();
            $table->date('required_date')->nullable();
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
        Schema::dropIfExists('scm_pr_lines');
    }
};
