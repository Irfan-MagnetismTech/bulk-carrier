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
        Schema::create('crw_crew_profiles', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('crw_recruitment_approval_id')->nullable();
			$table->enum('hired_by', ['Agency', 'Company'])->nullable();
			$table->unsignedBigInteger('agency_id')->nullable();
			$table->string('department_name');
			$table->unsignedBigInteger('crw_rank_id')->nullable();
			$table->string('employee_type');
			$table->tinyInteger('is_officer');
			$table->string('full_name');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('father_name');
			$table->string('mother_name');
			$table->date('date_of_birth');
			$table->string('gender');
			$table->string('religion');
			$table->string('marital_status');
			$table->string('nationality');
			$table->string('nid_no')->nullable();
			$table->string('passport_no')->nullable();
			$table->date('passport_issue_date')->nullable();
			$table->string('blood_group')->nullable();
			$table->string('height')->nullable();
			$table->string('weight')->nullable();
			$table->string('pre_address');
			$table->string('pre_city');
			$table->string('pre_mobile_no');
			$table->string('pre_email')->nullable();
			$table->string('per_address');
			$table->string('per_city');
			$table->string('per_mobile_no');
			$table->string('per_email')->nullable();
			$table->string('picture')->nullable();
			$table->string('attachment')->nullable();
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
        Schema::dropIfExists('crw_crew_profiles');
    }
};
