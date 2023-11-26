<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccFixedAsset;
use Modules\Accounts\Http\Requests\AccFixedAssetRequest;

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
            $accFixedAssets = AccFixedAsset::with('fixedAssetCosts', 'account:id,account_name', 'costCenter', 'scmMaterial:id,name')
            ->globalSearch($request->all());

            return response()->json([
                'status' => 'success',
                'value'  => $accFixedAssets,
            ], 200);
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
    public function store(AccFixedAssetRequest $request)
    {
        try {
            $accFixedAssetData = $request->only('acc_cost_center_id', 'scm_mrr_id', 'scm_material_id', 'brand', 'model', 'serial', 'acc_parent_account_id', 'acc_account_id', 'asset_tag', 'location', 'acquisition_date', 'useful_life', 'depreciation_rate', 'acquisition_cost', 'business_unit');
            $accFixedAsset     = AccFixedAsset::create($accFixedAssetData);
            $accFixedAsset->fixedAssetCosts()->createMany($request->fixedAssetCosts);

            return response()->json([
                'status' => 'success',
                'value'  => $accFixedAsset,
            ], 200);
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
            return response()->json([
                'status' => 'success',
                'value'  => $accFixedAsset->load('fixedAssetCosts', 'account:id,account_name', 'costCenter:id,name', 'scmMaterial:id,name'),
            ], 200);
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
            $accFixedAsset->update($accFixedAssetData);
            $accFixedAsset->fixedAssetCosts()->delete();
            $accFixedAsset->fixedAssetCosts()->createMany($request->fixedAssetCosts);

            return response()->json([
                'status' => 'success',
                'value'  => $accFixedAsset,
            ], 200);
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

            return response()->json([
                'status' => 'success',
                'value'  => null,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}