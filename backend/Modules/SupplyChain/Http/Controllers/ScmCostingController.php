<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmCosting;
use Modules\SupplyChain\Http\Requests\ScmCostingRequest;

class ScmCostingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $movementOuts = ScmCosting::with('scmCostingLines.scmLcRecord','scmPo','scmWarehouse')
                ->globalSearch(request()->all());

            return response()->success('Data list', $movementOuts, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('supplychain::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ScmCostingRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = ScmCosting::create($request->all());
            if($request->purchase_center == 'Foreign'){
                $lines = [];
                foreach ($request->scmCostingLines as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                            foreach ($value3 as $key4 => $value4) {
                                $lines[] = $value4;
                            }
                    }
                    }
                }
                $data->scmCostingLines()->createMany($lines);
            }else{
                $lines = [];
                foreach ($request->scmCostingLines as $key => $value) {
                    $lines[] = [
                        'scm_lc_record_id' => null,
                        'particulars' => $value['particulars'],
                        'exchange_rate' => 0,
                        'bdt_amount' => $value['bdt_amount'],
                        'usd_amount' => 0,
                        'type' => 'general'
                    ];
                }
                $data->scmCostingLines()->createMany($lines);
            }
            $data->scmCostingAllocations()->createMany($request->scmCostingAllocations);
            DB::commit();

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }


        return response()->json($lines, 422);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
        $data = ScmCosting::find($id);
        $data->load('scmCostingLines.scmLcRecord','scmPo','scmWarehouse','scmCostingAllocations.scmMrr');
        if($data->scmPo->purchase_center == 'Foreign'){
            $data2[] = $data->scmCostingLines->groupBy(['scm_lc_record_id', 'type']);
            data_forget($data, 'scmCostingLines');
            $data['scmCostingLines'] = $data2;
        }

        return response()->success('data', $data, 200);
    } catch (\Exception $e) {

        return response()->error($e->getMessage(), 500);
    }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supplychain::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ScmCostingRequest $request, $id)
    {
        try{
            DB::beginTransaction();
            $data = ScmCosting::find($id);
            $data->scmCostingLines()->delete();
            $data->scmCostingAllocations()->delete();
            $data->update($request->all());
            if($request->purchase_center == 'Foreign'){
                $lines = [];
                foreach ($request->scmCostingLines as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                            foreach ($value3 as $key4 => $value4) {
                                $lines[] = $value4;
                            }
                    }
                    }
                }
                $data->scmCostingLines()->createMany($lines);
            }else{
                $lines = [];
                foreach ($request->scmCostingLines as $key => $value) {
                    $lines[] = [
                        'scm_lc_record_id' => null,
                        'particulars' => $value['particulars'],
                        'exchange_rate' => 0,
                        'bdt_amount' => $value['bdt_amount'],
                        'usd_amount' => 0,
                        'type' => 'general'
                    ];
                }
                $data->scmCostingLines()->createMany($lines);
            }
            $data->scmCostingAllocations()->createMany($request->scmCostingAllocations);
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }


        return response()->json($lines, 422);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function fetchTry()
    {
        $data = ScmCosting::find(1);
        $data->load('scmCostingLines.scmLcRecord');
        // scmCostingLines is groupBy  scm_lc_record_id, type
        $data2[] = $data->scmCostingLines->groupBy(['scm_lc_record_id', 'type']);
        data_forget($data, 'scmCostingLines');
        $data['scmCostingLines'] = $data2;
        return response()->json($data, 200);
    }
}




