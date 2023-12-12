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
        Schema::create('ops_charterer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('company_legal_name')->unique();
            $table->string('name');
            $table->string('owner_code')->unique();
            $table->string('country');
            $table->string('contact_no');
            $table->string('address');
            $table->string('billing_address');
            $table->string('billing_email');
            $table->string('email');
            $table->string('website')->nullable();
            $table->enum('business_unit', ['PSML', 'TSLL','ALL'])->nullable(); 
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
        Schema::dropIfExists('ops_charterer_profiles');
    }
};
