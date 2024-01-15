<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWr;
use Modules\SupplyChain\Entities\ScmWrLine;
use Illuminate\Contracts\Support\Renderable;
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
    public function index(Request $request) : JsonResponse
    {
        try {
            $scmWr = ScmWr::with('scmWrLines.scmService', 'scmWrLines.user', 'scmWarehouse', 'user')
            ->globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $scmWr, 200);
        }
        catch (QueryException $e)
        {
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
                'attachment',
                'scmWrLines'
            );

            if (count($request->scmWrLines)<1) {
                $error= [
                    'message'=>'Must be add at least one service.',
                    'errors'=>[
                        'service'=>['Must be add at least one service.']
                        ]
                    ];
                return response()->json($error, 422);
            }

            if(isset($request->attachment)){
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/work_requisitions');
                $work_requisition_info['attachment'] = $attachment;
            }
            $work_requisition = ScmWr::create($work_requisition_info);

            $work_requisition->scmWrLines()->createMany($request->scmWrLines);
            DB::commit();
            return response()->success('Data added successfully.', $work_requisition, 201);
        }
        catch (QueryException $e)
        {
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
        $loggedInUserId = Auth::id();
        $work_requisition->load('scmWrLines.scmService', 'scmWrLines.user', 'scmWarehouse', 'user');
        $work_requisition->scmWrLines->each(function ($line) use ($loggedInUserId) {
            if ($line->closed_by == $loggedInUserId) {
                $line->user->name = 'You';
            }
        });

        if ($work_requisition->closed_by == $loggedInUserId) {
            $work_requisition->user->name = 'You';
        }

        try
        {
            return response()->success('Data retrieved successfully.', $work_requisition, 200);
        }
        catch (QueryException $e)
        {
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
        try {
            DB::beginTransaction();
            $work_requisition_info = $request->except(
                '_token',
                'attachment',
                'scmWrLines'
            );

            if (count($request->scmWrLines)<1) {
                $error= [
                    'message'=>'Must be add at least one service.',
                    'errors'=>[
                        'service'=>['Must be add at least one service.']
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
            $work_requisition->scmWrLines()->createMany($request->scmWrLines);
            DB::commit();
            return response()->success('Data updated successfully.', $work_requisition, 202);
        }
        catch (QueryException $e)
        {
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
        try
        {
            if(isset($work_requisition->attachment)){
                $this->fileUpload->deleteFile($work_requisition->attachment);
            }
            $work_requisition->scmWrLines()->delete();
            $work_requisition->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // public function getServidByPrIdForCs(Request $request): JsonResponse
    // {
    //     $lineData = ScmWrLine::query()
    //         ->with('scmService')
    //         ->where('scm_wr_id', $request->scm_wr_id)
    //         ->whereHas('scmWr', function ($query) {
    //             $query->whereIn('status', ['Pending', 'WIP']);
    //         })
    //         ->get();
    //         // ->map(function ($item) {
    //         //     $data = $item->scmService;
    //         //     $data['wr_composite_key'] = $item->wr_composite_key;
    //         //     return $data;
    //         // });

    //     return response()->success('Search result', $lineData, 200);
    // }

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

            return response()->success('Data updated sucessfully!',[$wrLines,$sumIsClosed], 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

}
