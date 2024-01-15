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
        Schema::create('scm_wr_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scm_wr_id')->constrained('scm_wrs')->onDelete('cascade');
            $table->bigInteger('scm_service_id')->nullable();
            $table->float('quantity', 20, 2)->nullable();
            $table->date('required_date')->nullable();
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable();
            $table->tinyInteger('is_closed')->default(0)->comment('0, 1');
            $table->integer('closed_by')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->string('closing_remarks')->nullable();
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
        Schema::dropIfExists('scm_wr_lines');
    }
};
