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
        Schema::create('ops_charterer_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ops_charterer_profile_id')->constrained('ops_charterer_profiles')->onDelete('cascade');
            $table->bigInteger('bank_id')->nullable();
            $table->bigInteger('bank_branch_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('account_name');
            $table->string('account_no');
            $table->string('swift_code')->nullable();
            $table->string('routing_no')->nullable();
            $table->string('currency')->nullable();
            $table->string('country')->nullable();
            $table->string('state_division')->nullable();
            $table->string('city')->nullable();
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
        Schema::dropIfExists('ops_charterer_bank_accounts');
    }
};
