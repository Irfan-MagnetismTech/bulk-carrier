<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SupplyChain\Services\AccountDataCreation;

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
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        (new AccountDataCreation)();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //on rollback ignore forign key
        Schema::disableForeignKeyConstraints();

        // foreach (['PSML', 'TSLL'] as $businessUnit) {
        //     DB::table('acc_accounts')
        //         ->where('accountable_type', ScmMaterialCategory::class)
        //         ->where('accountable_id', 1)
        //         ->where('account_type', config('accounts.account_types.Assets'))
        //         ->where('business_unit', $businessUnit)
        //         ->delete();
        // }

        Schema::dropIfExists('scm_material_categories');
    }
};
