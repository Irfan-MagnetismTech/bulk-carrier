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
        Schema::create('crw_crew_document_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crw_crew_document_id');
            $table->foreign('crw_crew_document_id', 'fk_crw_crew_document_renewal_id')->references('id')->on('crw_crew_documents')->cascadeOnDelete();
			$table->date('issue_date')->nullable();
			$table->date('expire_date')->nullable();
			$table->string('reference_no')->nullable();
			$table->string('attachment')->nullable();
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
        Schema::dropIfExists('crw_crew_document_renewals');
    }
};
