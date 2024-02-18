<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Entities\ScmLcRecordStatus;

class ScmLcRecordStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('supplychain::index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // $lcRecord= ScmLcRecord::where('id', $request->lc_record_id)->first();
            // $lcRecord->scmLcRecordStatuses()->createUpdateOrDelete(json_decode($request->scmLcRecordStatuses, true));

            $scmLcRecordStatus = ScmLcRecordStatus::create($request->all());

            DB::commit();
            return response()->success('Data created succesfully', $scmLcRecordStatus, 201);
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
        try {
            $scmLcRecordStatus = ScmLcRecordStatus::where('scm_lc_record_id', $id)
                ->latest('date')
                ->get();
            $latestRecord = $scmLcRecordStatus->first();
            
            return response()->success('LC Status Data',['scmLcRecordStatus'=>$scmLcRecordStatus, 'last_date'=>(!empty($latestRecord)?$latestRecord->date:null)] , 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function lcStatusDelete(Request $request)
    {
        // return response()->json($request->all(), 422);
        try {
            $scmLcRecordStatus = ScmLcRecordStatus::where(['id' => $request->status_id, 'scm_lc_record_id' => $request->scm_lc_record_id])->first();
            $scmLcRecordStatus->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }

    }
}
