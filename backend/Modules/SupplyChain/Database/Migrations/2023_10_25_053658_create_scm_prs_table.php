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
        Schema::create('scm_prs', function (Blueprint $table) {
            $table->comment('pr => Purchase Requisition');
            $table->id();
            $table->string('ref_no')->nullable();
            $table->foreignId('scm_warehouse_id')->constrained('scm_warehouses')->nullable();
            $table->bigInteger('acc_cost_center_id')->nullable();
            $table->string('attachment')->nullable();
            $table->date('raised_date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('purchase_center')->nullable()->comment('local, foreign, plant');
            $table->tinyInteger('is_critical')->default(0)->comment('0, 1');
            $table->date('approved_date')->nullable();
            $table->tinyInteger('is_closed')->default(0)->comment('0, 1');
            $table->integer('closed_by')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->string('closing_remarks')->nullable();
            $table->string('business_unit')->nullable();
            $table->bigInteger('created_by')->comment('user_id');
            $table->enum('status', ['Pending', 'WIP', 'Closed'])->default('Pending');
            $table->string('department')->nullable();
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
        Schema::dropIfExists('scm_prs');
    }
};
