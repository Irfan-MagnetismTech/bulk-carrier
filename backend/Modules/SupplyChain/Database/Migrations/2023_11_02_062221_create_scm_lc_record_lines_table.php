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
        Schema::create('scm_lc_record_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_lc_record_id')->constrained('scm_lc_records')->cascadeOnDelete();
            $table->string('particular')->nullable();
            $table->decimal('amount')->nullable();
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
        Schema::dropIfExists('scm_lc_record_lines');
    }
};
