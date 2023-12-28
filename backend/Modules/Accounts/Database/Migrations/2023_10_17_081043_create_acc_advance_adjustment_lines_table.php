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
        Schema::create('acc_advance_adjustment_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('acc_advance_adjustment_id')->constrained('acc_advance_adjustments', 'id')->cascadeOnDelete();
			$table->string('particular');
            $table->decimal('amount', 10, 2);
			$table->text('attachment')->nullable();
			$table->text('remarks')->nullable();
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
        Schema::dropIfExists('acc_advance_adjustment_lines');
    }
};
