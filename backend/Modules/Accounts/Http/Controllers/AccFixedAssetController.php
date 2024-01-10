<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccFixedAsset;
use Modules\Accounts\Http\Requests\AccFixedAssetRequest;
use Modules\Accounts\Services\AccountService;

class AccFixedAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accFixedAssets = AccFixedAsset::with('fixedAssetCosts', 'account:id,account_name', 'costCenter', 'scmMaterial:id,name', 'fixedAssetCategory', 'scmMrr')
                ->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accFixedAssets, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $accFixedAssetData = $request->only('acc_cost_center_id', 'scm_mrr_id', 'scm_material_id', 'brand', 'model', 'serial', 'acc_parent_account_id', 'acc_account_id', 'asset_tag', 'location', 'acquisition_date', 'useful_life', 'depreciation_rate', 'acquisition_cost', 'business_unit');

            
            DB::transaction(function () use ($request, $accFixedAssetData)
            {
                $accFixedAsset = AccFixedAsset::create($accFixedAssetData);
                $accFixedAsset->fixedAssetCosts()->createMany($request->fixedAssetCosts);
                
                // $codeData = [
                //     'base_header' => config('accounts.account_types.assets'),
                //     'header'      => config('accounts.balance_income_balance_header.non_current_assets'),
                //     'line'        => config('accounts.balance_income_line.fixed_assets_at_cost'),
                //     'id'          => $accFixedAsset->id,
                // ];
                // $accountCode = (new AccountService())->generateAccountCode($codeData);
                $accountData = [
                    'balance_income_line_id' => config('accounts.balance_income_base_header.fixed_assets_at_cost'),
                    'name' => $accFixedAsset->scmMaterial->name,
                    'code' => '33',
                    'type' => config('accounts.account_types.Assets'),
                    'unit' => $accFixedAsset->business_unit,
                    'accountable_type' => AccFixedAsset::class,
                    'accountable_id' => $accFixedAsset?->id,
                ];

                $data = (new AccountService())->handleAccountService($accountData);
                
                dd($data);
                return response()->json($data);
                
            });

            return response()->success('Created Successfully', '', 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccFixedAsset  $accFixedAsset
     * @return \Illuminate\Http\Response
     */
    public function show(AccFixedAsset $accFixedAsset)
    {
        try {

            return response()->success('Retrieved Successfully',
                $accFixedAsset->load('fixedAssetCosts', 'account', 'costCenter', 'scmMrr', 'scmMaterial.account', 'fixedAssetCategory'),
                200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccFixedAsset  $accFixedAsset
     * @return \Illuminate\Http\Response
     */
    public function update(AccFixedAssetRequest $request, AccFixedAsset $accFixedAsset)
    {
        try {
            $accFixedAssetData = $request->only('acc_cost_center_id', 'scm_mrr_id', 'scm_material_id', 'brand', 'model', 'serial', 'acc_parent_account_id', 'acc_account_id', 'asset_tag', 'location', 'acquisition_date', 'useful_life', 'depreciation_rate', 'acquisition_cost', 'business_unit');

            DB::transaction(function () use ($request, $accFixedAssetData, $accFixedAsset)
            {
                $accFixedAsset->update($accFixedAssetData);
                $accFixedAsset->fixedAssetCosts()->delete();
                $accFixedAsset->fixedAssetCosts()->createMany($request->fixedAssetCosts);
            });

            return response()->success('Updated Successfully', '', 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccFixedAsset  $accFixedAsset
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccFixedAsset $accFixedAsset)
    {
        try {
            $accFixedAsset->delete();

            return response()->success('Deleted Successfully', null, 204);

        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
