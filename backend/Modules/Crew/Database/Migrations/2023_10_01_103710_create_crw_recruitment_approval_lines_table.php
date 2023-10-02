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
        Schema::create('crw_recruitment_approval_lines', function (Blueprint $table) {
            $table->id();
			$table->foreignId('crw_recruitment_approval_id')->constrained('crw_recruitment_approvals', 'id')->cascadeOnDelete();
			$table->unsignedInteger('crw_rank_id');
			$table->string('candidate_name');
			$table->string('candidate_contact');
			$table->string('candidate_email')->nullable();
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
        Schema::dropIfExists('crw_recruitment_approval_lines');
    }
};
