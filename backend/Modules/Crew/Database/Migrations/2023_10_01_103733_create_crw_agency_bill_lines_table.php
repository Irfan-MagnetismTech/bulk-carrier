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
        Schema::create('crw_agency_bill_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_agency_bill_id')->constrained('crw_agency_bills', 'id')->cascadeOnDelete();
			$table->string('particular');
			$table->text('description')->nullable();
			$table->string('per')->nullable();
			$table->integer('quantity');
			$table->decimal('rate');
			$table->decimal('amount');
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
        Schema::dropIfExists('crw_agency_bill_lines');
    }
};
