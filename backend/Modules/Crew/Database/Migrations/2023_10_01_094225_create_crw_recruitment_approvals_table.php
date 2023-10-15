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
        Schema::create('crw_recruitment_approvals', function (Blueprint $table) {
            $table->id();
			$table->date('applied_date');
			$table->string('page_title');
			$table->string('subject');
			$table->integer('total_approved');
			$table->integer('crew_agreed_to_join');
			$table->integer('crew_selected');
			$table->integer('crew_panel');
			$table->integer('crew_rest');
			$table->text('body');
			$table->text('remarks')->nullable();
			$table->enum('business_unit', ['PSML', 'TSLL']);
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
        Schema::dropIfExists('crw_recruitment_approvals');
    }
};
