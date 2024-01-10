<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccAccount;
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

                $FA_AccountCode = config('accounts.balance_income_base_header.assets').'-'.
                                  config('accounts.balance_income_balance_header.non_current_assets').'-'.
                                  config('accounts.balance_income_line.fixed_assets_at_cost').'-'.
                                  $accFixedAsset->id;
                $AD_AccountCode = config('accounts.balance_income_base_header.assets').'-'.
                                  config('accounts.balance_income_balance_header.non_current_assets').'-'.
                                  config('accounts.balance_income_line.acumulated_depreciation').'-'.
                                  $accFixedAsset->id;
                $Dep_AccountCode =config('accounts.balance_income_base_header.expenses').'-'.
                                  config('accounts.balance_income_balance_header.indirect_exp').'-'.
                                  config('accounts.balance_income_line.administrative_expenses').'-'.
                                  $accFixedAsset->id;

                $FA_accountData = [
                    'acc_balance_and_income_line_id' => config('accounts.balance_income_line.fixed_assets_at_cost'),
                    'account_name'                   => 'FA-'.$accFixedAsset->scmMaterial->name,
                    'account_code'                   => $FA_AccountCode,
                    'account_type'                   => config('accounts.account_types.Assets'),
                    'business_unit'                  => $accFixedAsset->business_unit,
                    'parent_account_id'              => $accFixedAsset->acc_parent_account_id,
                ];
                $AD_accountData = [
                    'acc_balance_and_income_line_id' => config('accounts.balance_income_line.acumulated_depreciation'),
                    'account_name'                   => 'AD-'.$accFixedAsset->scmMaterial->name,
                    'account_code'                   => $AD_AccountCode,
                    'account_type'                   => config('accounts.account_types.Assets'),
                    'business_unit'                  => $accFixedAsset->business_unit,
                ];
                $dep_accountData = [
                    'acc_balance_and_income_line_id' => config('accounts.balance_income_line.administrative_expenses'),
                    'account_name'                   => 'Dep-'.$accFixedAsset->scmMaterial->name,
                    'account_code'                   => $Dep_AccountCode,
                    'account_type'                   => config('accounts.account_types.Expenses'),
                    'business_unit'                  => $accFixedAsset->business_unit,
                ];
                // dd($accFixedAsset);
                // $FA_line_id = config('accounts.balance_income_line.fixed_assets_at_cost');
                // $name       = $accFixedAsset->scmMaterial->name;
                // $FA_account = config('accounts.account_types.Assets');
                // // dd('kk');
                // $data = (new AccountService())->handleAccountService($FA_line_id,$name,$accountCode,$FA_account,$accFixedAsset->business_unit,AccFixedAsset::class,$accFixedAsset->id);

                // dd($FA_line_id,$accountCode,$FA_account,$accFixedAsset->business_unit,AccFixedAsset::class,$accFixedAsset->id);
                // return response()->json($data);
                // dd($data);

                $accFixedAsset->morphaccount()->create($FA_accountData);
                $accFixedAsset->morphaccount()->create($AD_accountData);
                $accFixedAsset->morphaccount()->create($dep_accountData);

                //Account Transection Process
                $transectionData = [
                    'acc_cost_center_id'    => $accFixedAsset->acc_cost_center_id,
                    'voucher_type'          => 'Journal',
                    'transaction_date'      => $accFixedAsset->acquisition_date,
                    'narration'             => '',
                    'business_unit'         => $accFixedAsset->business_unit,
                ];

                $FA_ledgers = [
                    'acc_cost_center_id'            => $accFixedAsset->acc_cost_center_id,
                    'acc_balance_and_income_line_id'=> $accFixedAsset->fixedAssetAccount->acc_balance_and_income_line_id,
                    'acc_account_id'                => $accFixedAsset->fixedAssetAccount->id,
                    'dr_amount'                     => $accFixedAsset->fixedAssetCosts()->first()->amount,
                ];

                $Inv_ledgers = [
                    'acc_cost_center_id'            => $accFixedAsset->acc_cost_center_id,
                    'acc_balance_and_income_line_id'=> $accFixedAsset->account->acc_balance_and_income_line_id,
                    'acc_account_id'                => $accFixedAsset->account->id,
                    'cr_amount'                     => $accFixedAsset->fixedAssetCosts()->first()->amount,
                ];

                $transection =  $accFixedAsset->transaction()->create($transectionData);
                $transection->ledgerEntries()->create($FA_ledgers);
                $transection->ledgerEntries()->create($Inv_ledgers);
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
                $accFixedAsset->load('fixedAssetCosts', 'account', 'costCenter', 'scmMrr', 'scmMaterial.account', 'fixedAssetCategory',
                'fixedAssetAccount','acumulateDepreciationAccount','depreciationAccount'),
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

                AccAccount::where('id', $accFixedAsset->fixedAssetAccount->id)->update(['account_name' => 'FA-' . $accFixedAsset->scmMaterial->name]);
                AccAccount::where('id', $accFixedAsset->acumulateDepreciationAccount->id)->update(['account_name' => 'AD-' . $accFixedAsset->scmMaterial->name]);
                AccAccount::where('id', $accFixedAsset->depreciationAccount->id)->update(['account_name' => 'Dep-' . $accFixedAsset->scmMaterial->name]);

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
