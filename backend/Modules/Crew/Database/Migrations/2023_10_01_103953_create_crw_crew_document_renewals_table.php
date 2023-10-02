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
			$table->foreignId('crw_crew_document_id')->constrained('crw_crew_documents', 'id')->cascadeOnDelete();
			$table->date('issue_date');
			$table->date('expire_date');
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
