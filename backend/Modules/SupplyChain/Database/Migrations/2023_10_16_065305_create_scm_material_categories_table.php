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
        Schema::create('scm_material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('short_code')->unique()->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->timestamps();
        });

        // Insert seed data
        DB::table('scm_material_categories')->insert([
            [
                'name' => 'Bunker',
                'short_code' => 'bunker',                
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::dropIfExists('scm_material_categories');
    }
};
