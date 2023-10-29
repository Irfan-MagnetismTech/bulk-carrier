<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('scm_units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('short_code')->unique()->nullable();
            $table->timestamps();
        });

        // Insert seed data
        DB::table('scm_units')->insert([
            [
                'name' => 'KG',
                'short_code' => 'kg',
            ],
            [
                'name' => 'TON',
                'short_code' => 'ton',
            ],
            [
                'name' => 'Meter',
                'short_code' => 'meter',
            ],
            [
                'name' => 'Liter',
                'short_code' => 'liter',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scm_units');
    }
};
