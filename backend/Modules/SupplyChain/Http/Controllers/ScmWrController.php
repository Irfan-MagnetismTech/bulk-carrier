<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWo;
use Modules\SupplyChain\Entities\ScmWr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmWoLine;
use Modules\SupplyChain\Entities\ScmWrLine;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmWcsService;
use Modules\SupplyChain\Http\Requests\ScmWrRequest;

class ScmWrController extends Controller
{
    function __construct(private FileUploadService $fileUpload)
    {
        //     $this->middleware('permission:work-requisition-create|work-requisition-edit|work-requisition-show|work-requisition-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:work-requisition-create', ['only' => ['store']]);
        //     $this->middleware('permission:work-requisition-edit', ['only' => ['update']]);
        //     $this->middleware('permission:work-requisition-delete', ['only' => ['destroy']]);
    }
    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $scmWr = ScmWr::with('scmWrLines.scmService', 'scmWrLines.closedBy', 'scmWrLines.createdBy', 'scmWarehouse', 'closedBy', 'createdBy')
                ->globalSearch($request->all());

            // $loggedInUserId = Auth::id();

            // $scmWr->each(function ($wr) use ($loggedInUserId) {
            //     if ($wr->closed_by == $loggedInUserId) {
            //         $wr->closedBy->name = 'You';
            //     }
            //     if ($wr->created_by == $loggedInUserId) {
            //         $wr->createdBy->name = 'You';
            //     }
            // });

            return response()->success('Data retrieved successfully.', $scmWr, 200);
        } catch (QueryException $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ScmWrRequest $request
     * @return JsonResponse
     */
    public function store(ScmWrRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $work_requisition_info = $request->except(
                '_token',
                'ref_no',
                'attachment',
                'scmWrLines'
            );

            $work_requisition_info['ref_no'] = UniqueId::generate(ScmWr::class, 'WR');
            $work_requisition_info['created_by'] = auth()->id();

            if (count($request->scmWrLines) < 1) {
                $error = [
                    'message' => 'Must be add at least one service.',
                    'errors' => [
                        'service' => ['Must be add at least one service.']
                    ]
                ];
                return response()->json($error, 422);
            }

            if (isset($request->attachment)) {
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/work_requisitions');
                $work_requisition_info['attachment'] = $attachment;
            }

            // $modifiedLines = collect($request->scmWrLines)->map(function ($lineData) {
            //     $lineData['created_by'] = auth()->id();
            //     $lineData['wr_composite_key'] = CompositeKey::generate(ScmWrLine::class, 'WR', $lineData['scm_wr_id'], $lineData['scm_service_id']);
            //     return $lineData;
            // })->toArray();

            $work_requisition = ScmWr::create($work_requisition_info);

            $linesData = CompositeKey::generateArray($request->scmWrLines, $work_requisition->id, 'scm_service_id', 'wr');

            $work_requisition->scmWrLines()->createMany($linesData);

            DB::commit();
            return response()->success('Data added successfully.', $work_requisition, 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified work requisition.
     *
     * @param  ScmWr  $work_requisition
     * @return JsonResponse
     */
    public function show(ScmWr $work_requisition): JsonResponse
    {
        // dd('dddd');
        // $loggedInUserId = Auth::id();
        $work_requisition->load('scmWrLines.scmService', 'scmWrLines.closedBy', 'scmWrLines.createdBy', 'scmWarehouse', 'closedBy', 'createdBy');
        // $work_requisition->scmWrLines->each(function ($line) use ($loggedInUserId) {
        //     if ($line->closed_by == $loggedInUserId) {
        //         $line->closedBy->name = 'You';
        //     }
        //     if ($line->created_by == $loggedInUserId) {
        //         $line->createdBy->name = 'You';
        //     }
        // });

        // if ($work_requisition->closed_by == $loggedInUserId) {
        //     $work_requisition->closedBy->name = 'You';
        // }

        // if ($work_requisition->created_by == $loggedInUserId) {
        //     $work_requisition->createdBy->name = 'You';
        // }

        try {
            return response()->success('Data retrieved successfully.', $work_requisition, 200);
        } catch (QueryException $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ScmWrRequest $request
     * @param  OpsVesselParticular  $work_requisition
     * @return JsonResponse
     */
    public function update(ScmWrRequest $request, ScmWr $work_requisition): JsonResponse
    {
        $linesData = CompositeKey::generateArray($request->scmWrLines, $work_requisition->id, 'scm_material_id', 'pr');

        try {
            DB::beginTransaction();
            $work_requisition_info = $request->except(
                '_token',
                'ref_no',
                'attachment',
                'scmWrLines',
                'created_by'
            );

            if (count($request->scmWrLines) < 1) {
                $error = [
                    'message' => 'Must be add at least one service.',
                    'errors' => [
                        'service' => ['Must be add at least one service.']
                    ]
                ];
                return response()->json($error, 422);
            }

            // if(isset($request->attachment)){
            // $this->fileUpload->deleteFile($work_requisition->attachment);
            $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/work_requisitions', $work_requisition->attachment);
            $work_requisition_info['attachment'] = $attachment;
            // }

            $work_requisition->update($work_requisition_info);
            $work_requisition->scmWrLines()->delete();
            $work_requisition->scmWrLines()->createMany($linesData);
            DB::commit();
            return response()->success('Data updated successfully.', $work_requisition, 202);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified vessel from storage.
     *
     * @param  ScmWr  $vessel_particular
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ScmWr $work_requisition): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $woCount = ScmWoLine::where('scm_wr_id', $work_requisition->id)->count();
            $wcsCount = ScmWcsService::where('scm_wr_id', $work_requisition->id)->count();

            if ($woCount + $wcsCount > 0) {
                $error = array(
                    "message" => "Data could not be deleted!",
                    "errors" => [
                        "id" => ["This data could not be deleted as it has references in other table!"]
                    ]
                );
                return response()->json($error, 422);
            }

            if (isset($work_requisition->attachment)) {
                $this->fileUpload->deleteFile($work_requisition->attachment);
            }
            $work_requisition->scmWrLines()->delete();
            $work_requisition->delete();
            DB::commit();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json($work_requisition->preventDeletionIfRelated(), 422);
        }
    }


    public function getServiceByWrIdForWcs(Request $request): JsonResponse
    {
        $lineData = ScmWrLine::query()
        ->with('scmService')
        ->when($request->scm_wr_id, function ($query) use ($request) {
            $query->where('scm_wr_id', $request->scm_wr_id);
        })
        ->whereNot('status', 'Closed')
        ->whereHas('scmWr', function ($query) {
            $query->whereIn('status', ['Pending', 'WIP']);
        })
        ->get()
        ->map(function ($item) use ($request){
            $data = $item->scmService;
            $data['wr_composite_key'] = $item->wr_composite_key;
            $scmWcsService =null;
            if(isset($request->scm_wcs_id)){
                $scmWcsService = ScmWcsService::where(['scm_wr_id'=>$request->scm_wr_id,'scm_wcs_id'=>$request->scm_wcs_id, 'scm_service_id'=>$item->scm_service_id])->first();
            }
            if($scmWcsService){
                $max = $item->quantity - $item->scmWcsServices->sum('quantity') + $scmWcsService->quantity;
            }else{
                $max = $item->quantity - $item->scmWcsServices->sum('quantity');
            }
            $data['max_quantity'] = $max;
            return $data;
        });

        return response()->success('Search result', $lineData, 200);
    }



    public function searchWr(Request $request): JsonResponse
    {
        if (isset($request->searchParam)) {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->when($request->cost_center_id, function ($query) use ($request) {
                            $query->where('acc_cost_center_id', $request->cost_center_id);
                        });
                })
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        } else {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->where(function ($query) use ($request) {
                    $query->where('business_unit', $request->business_unit)
                        ->when($request->cost_center_id, function ($query) use ($request) {
                            $query->where('acc_cost_center_id', $request->cost_center_id);
                        });
                })
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        }

        return response()->success('Search result', $work_requisition, 200);
    }


    public function searchWorkRequisitions(Request $request): JsonResponse
    {
        $work_requisition =[];
        if (isset($request->searchParam)) {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->whereNot('status', 'Closed')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->when($request->scm_warehouse_id, function ($query) use ($request) {
                            $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                                ->where('purchase_center', $request->purchase_center);
                        });
                })
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } elseif (isset($request->scm_wcs_id) && isset($request->scm_warehouse_id) && isset($request->purchase_center) && isset($request->business_unit)) {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->whereNot('status', 'Closed')
                ->when($request->scm_warehouse_id, function ($query) use ($request) {
                    $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                    ->where('business_unit', $request->business_unit)
                    ->where('purchase_center', $request->purchase_center);
                })
                ->whereHas('scmWcsService', function ($query) use ($request) {
                    $query->where('scm_wcs_id', $request->scm_wcs_id);
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } elseif (isset($request->scm_warehouse_id) && isset($request->purchase_center) && isset($request->business_unit)) {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->whereNot('status', 'Closed')
                ->when($request->scm_warehouse_id, function ($query) use ($request) {
                    $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                        ->where('business_unit', $request->business_unit)
                        ->where('purchase_center', $request->purchase_center);
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        }

        return response()->success('Search result', $work_requisition, 200);
    }

    public function searchWorkRequisitionsForWcs(Request $request): JsonResponse
    {
        $work_requisition = [];
        if (isset($request->searchParam)) {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->whereNot('status', 'Closed')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->when($request->scm_warehouse_id, function ($query) use ($request) {
                            $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                                ->where('purchase_center', $request->purchase_center);
                        });
                })
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } elseif (isset($request->scm_warehouse_id) && isset($request->purchase_center) && isset($request->business_unit)) {
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->whereNot('status', 'Closed')
                ->when($request->scm_warehouse_id, function ($query) use ($request) {
                    $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                        ->where('business_unit', $request->business_unit)
                        ->where('purchase_center', $request->purchase_center);
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        }elseif(isset($request->business_unit) && isset($request->purchase_center)){
            $work_requisition = ScmWr::query()
                ->with('scmWrLines')
                ->whereNot('status', 'Closed')
                ->where(function ($query) use ($request) {
                    $query->when($request->purchase_center, function ($query) use ($request) {
                        $query->where('purchase_center', $request->purchase_center)
                        ->where('business_unit', $request->business_unit);
                    });
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        }

        return response()->success('Search result', $work_requisition, 200);
    }

    // for closing Work Requisition
    public function closeWr(Request $request): JsonResponse
    {
        try {
            $work_requisition = ScmWr::find($request->id);
            $work_requisition->update([
                // 'is_closed' => 1,
                'status' => 'Closed',
                'closed_by' => auth()->user()->id,
                'closed_at' => now(),
                'closing_remarks' => $request->closing_remarks,
            ]);

            $work_requisition->load('scmWrLines');
            foreach ($work_requisition->scmWrLines as $wrLine) {
                if ($wrLine->status === 'Closed') {
                    continue;
                }
                $wrLine->update([
                    // 'is_closed' => 1,
                    'status' => 'Closed',
                    'closed_by' => auth()->user()->id,
                    'closed_at' => now(),
                    'closing_remarks' => $request->closing_remarks,
                ]);
            }
            return response()->success('Data updated sucessfully!', $work_requisition, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function closeWrLine(Request $request): JsonResponse
    {
        try {
            $wrLine = ScmWrLine::find($request->id);
            $wrLine->update([
                // 'is_closed' => 1,
                'status' => 'Closed',
                'closed_by' => auth()->user()->id,
                'closed_at' => now(),
                'closing_remarks' => $request->closing_remarks,
            ]);

            $work_requisition = ScmWr::find($request->parent_id);
            $work_requisition->load('scmWrLines');

            $wrLines = $work_requisition->scmWrLines->count();
            // $sumIsClosed = $pr->scmWrLines->sum('is_closed');
            $sumIsClosed = $work_requisition->scmWrLines->where('status', 'Closed')->count();

            if ($wrLines === $sumIsClosed) {
                $work_requisition->update([
                    // 'is_closed' => 1,
                    'status' => 'Closed',
                    'closed_by' => auth()->user()->id,
                    'closed_at' => now(),
                    'closing_remarks' => "All lines are closed",
                ]);
            }

            return response()->success('Data updated sucessfully!', [$wrLines, $sumIsClosed], 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}