<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWcs;
use Modules\SupplyChain\Services\UniqueId;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmWcsService;
use Modules\SupplyChain\Http\Requests\ScmWcsRequest;

class ScmWcsController extends Controller
{
    function __construct()
    {
        //     $this->middleware('permission:charterer-contract-create|charterer-contract-edit|charterer-contract-show|charterer-contract-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:charterer-contract-create', ['only' => ['store']]);
        //     $this->middleware('permission:charterer-contract-edit', ['only' => ['update']]);
        //     $this->middleware('permission:charterer-contract-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $scmCs = ScmWcs::query()
                ->with('scmWarehouse', 'selectedVendors.scmVendor', 'scmWcsServices.scmService','scmWcsVendors.scmVendor' ,'scmWcsVendorServices.scmService', 'scmWcsVendorServices.scmWr')
                ->globalSearch(request()->all());

            return response()->success('Data list', $scmCs, 200);
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
    public function store(ScmWcsRequest $request)
    {
        $requestData = $request->except('ref_no','scmWcsServices');
        $requestData['ref_no'] = UniqueId::generate(ScmWcs::class, 'WCS');
        try {
            DB::beginTransaction();

            if (count($request->scmWcsServices)<1) {
                $error= [
                    'message'=>'Must be add at least one service.',
                    'errors'=>[
                        'service'=>['Must be add at least one service.']
                        ]
                    ];
                return response()->json($error, 422);
            }

            $scmWcs = ScmWcs::create($requestData);
            $scmWcs->scmWcsServices()->createMany($request->scmWcsServices);
            DB::commit();
            return response()->success('Data created succesfully', $scmWcs, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $serviceWcs = ScmWcs::find($id);
        $serviceWcs->load('scmWcsServices.scmService', 'scmWcsServices.scmWr', 'scmWarehouse');

        try {
            return response()->success('Detail data', $serviceWcs, 200);
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
    public function update(ScmWcsRequest $request, $id)
    {
        $scmWcs = ScmWcs::find($id);
        $requestData = $request->except('ref_no','scmWcsServices');
        try {
            DB::beginTransaction();

            if (count($request->scmWcsServices)<1) {
                $error= [
                    'message'=>'Must be add at least one service.',
                    'errors'=>[
                        'service'=>['Must be add at least one service.']
                        ]
                    ];
                return response()->json($error, 422);
            }

            $scmWcs->update($requestData);
            $scmWcs->scmWcsServices()->delete();
            $scmWcs->scmWcsServices()->createMany($request->scmWcsServices);
            
          
            DB::commit();
            return response()->success('Data updated succesfully', $scmWcs, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $scmWcs = ScmWcs::find($id);
        try {
            DB::beginTransaction();
            $scmWcs->scmWcsServices()->delete();
            $scmWcs->delete();
            DB::commit();
            return response()->success('Data deleted succesfully', null, 203);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json($scmWcs->preventDeletionIfRelated(), 422);
        }
    }

      /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getWcsWiseData($id)
    {
        $scmWcs = ScmWcs::find($id);
        // $scmWcs->load('scmPr', 'scmWarehouse');
        $scmWcs->load('scmWcsServices.scmService', 'scmWr', 'scmWcsServices.scmWr', 'scmWarehouse');
        //scmCsMaterials groupBy ['scm_material_id','scm_pr_id']
        $data = $scmWcs->scmWcsServices->groupBy(['scm_service_id'])->values()->all();
        data_forget($scmWcs, 'scmWcsServices');

        $scmWcs['scmWcsServices'] = $data;


        try {
            return response()->success('Detail data', $scmWcs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

}
