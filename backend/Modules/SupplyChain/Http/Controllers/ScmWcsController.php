<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWr;
use Modules\SupplyChain\Entities\ScmWcs;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmWoItem;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWcsVendor;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmWcsService;
use Modules\SupplyChain\Http\Requests\ScmWcsRequest;
use Modules\SupplyChain\Entities\ScmWcsVendorService;
use Modules\SupplyChain\Http\Requests\ScmWcsQuotationRequest;
use Modules\SupplyChain\Http\Requests\WcsSupplierSelectionRequest;

class ScmWcsController extends Controller
{
    function __construct(private FileUploadService $fileUpload)
    {
        //     $this->middleware('permission:work-cs-create|work-cs-edit|work-cs-show|work-cs-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:work-cs-create', ['only' => ['store']]);
        //     $this->middleware('permission:work-cs-edit', ['only' => ['update']]);
        //     $this->middleware('permission:work-cs-delete', ['only' => ['destroy']]);
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

            foreach($request->scmWcsServices as $service){
                $work_requisition = ScmWr::find($service['scm_wr_id']);
                if($work_requisition['status'] != 'WIP'){
                    $work_requisition->update([
                        'status' => 'WIP',
                    ]);
                    
                    $work_requisition->load('scmWrLines');
                    foreach ($work_requisition->scmWrLines as $wrLine) {
                        if ($wrLine['status'] === 'WIP') {
                            continue;
                        }
                        $wrLine->update([
                            'status' => 'WIP'
                        ]);
                    }
                }
            }

            $scmWcs = ScmWcs::create($requestData);

            foreach ($request->scmWcsServices as $key => $value) {
                ScmWcsService::create([
                    'scm_wcs_id' => $scmWcs->id,
                    'scm_wr_id' => $value['scm_wr_id'],
                    'scm_service_id' => $value['scm_service_id'],
                    'wcs_composite_key' => CompositeKey::generate(null, $scmWcs->id, 'wcs', $value['scm_service_id'], $value['scm_wr_id']),
                    'wr_composite_key' => $value['wr_composite_key'],
                    'quantity' => $value['quantity'],
                ]);
            }

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
    public function show(ScmWcs $work_c): JsonResponse
    {
        $work_c->load('scmWcsServices.scmService', 'scmWcsServices.scmWr','scmWcsServices.scmWrLine', 'scmWarehouse');

        $data = $work_c->scmWcsServices->map(function($service){
            $service['wr_quantity']= $service->scmWrLine->quantity;
            $service['max_quantity']= $service->scmWrLine->quantity - $service->scmWrLine->scmWcsServices->sum('quantity') + $service->quantity;

            return $service;
        });

        data_forget($work_c, 'scmWcsServices');
        $work_c['scmWcsServices']= $data;


        try {
            return response()->success('Detail data', $work_c, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ScmWcsRequest $request, ScmWcs $work_c)
    {
        // $scmWcs = ScmWcs::find($id);
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

            foreach($request->scmWcsServices as $service){
                $work_requisition = ScmWr::find($service['scm_wr_id']);
                if($work_requisition['status'] != 'WIP'){
                    $work_requisition->update([
                        'status' => 'WIP',
                    ]);
                    
                    $work_requisition->load('scmWrLines');
                    foreach ($work_requisition->scmWrLines as $wrLine) {
                        if ($wrLine['status'] === 'WIP') {
                            continue;
                        }
                        $wrLine->update([
                            'status' => 'WIP'
                        ]);
                    }
                }
            }

            $work_c->update($requestData);
            $work_c->scmWcsServices()->delete();

            foreach ($request->scmWcsServices as $key => $value) {
                ScmWcsService::create([
                    'scm_wcs_id' => $work_c->id,
                    'scm_wr_id' => $value['scm_wr_id'],
                    'scm_service_id' => $value['scm_service_id'],
                    'wcs_composite_key' => CompositeKey::generate(null, $work_c->id, 'wcs', $value['scm_service_id'], $value['scm_wr_id']),
                    'wr_composite_key' => $value['wr_composite_key'],
                    'quantity' => $value['quantity'],
                ]);
            }

            DB::commit();
            return response()->success('Data updated succesfully', $work_c, 202);
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
    public function destroy(ScmWcs $work_c)
    {
        // $scmWcs = ScmWcs::find($id);
        $work_c->load('scmWcsServices');
        try {
            DB::beginTransaction();
            foreach($work_c->scmWcsServices as $service){
                $work_cs_wr = ScmWcsService::where('scm_wr_id',$service->scm_wr_id)->where('id','!=',$service->id)->get();

                if(count($work_cs_wr)==0){
                    $work_requisition = ScmWr::find($service->scm_wr_id);
                    $work_requisition->update([
                        'status' => 'Pending',
                    ]);
                    
                    $work_requisition->load('scmWrLines');
                    foreach ($work_requisition->scmWrLines as $wrLine) {
                        if ($wrLine->status === 'Pending') {
                            continue;
                        }
                        $wrLine->update([
                            'status' => 'Pending'
                        ]);
                    }
                }

            }

            $work_c->scmWcsServices()->delete();
            $work_c->delete();
            DB::commit();
            return response()->success('Data deleted succesfully', null, 203);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json($work_c->preventDeletionIfRelated(), 422);
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
        $scmWcs->load('scmWcsServices.scmService', 'scmWcsServices.scmWr', 'scmWarehouse');
        $data = $scmWcs->scmWcsServices->groupBy(['scm_service_id'])->values()->all();
        data_forget($scmWcs, 'scmWcsServices');

        $scmWcs['scmWcsServices'] = $data;


        try {
            return response()->success('Detail data', $scmWcs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getWcsQuotations(Request $request)
    {
        $scmWcs = ScmWcsVendor::query()
            ->with('scmWcs.scmWcsVendors', 'scmVendor.scmVendorContactPerson', 'scmWcsVendorServices')
            ->when($request->scm_wcs_id, function ($query) use ($request) {
                $query->where('scm_wcs_id', $request->scm_wcs_id);
            })
            ->get();

        return response()->success('Data list', $scmWcs, 200);
    }


    public function storeWcsQuotation(ScmWcsQuotationRequest $request)
    {
        try {
            // ScmCs::find($request->scm_cs_id)->update(['status' => 'quotation']);
            $scmWcs = ScmWcs::find($request->scm_wcs_id);
            $requestData = $request->only(
                'scm_vendor_id',
                'scm_wcs_id',
                'quotation_ref_no',
                'quotation_date',
                'quotation_validity',
                'payment_mode',
                'credit_term',
                'vat',
                'ait',
                'currency',
                'security_money',
                'adjustment_policy',
                'is_selected',
                'terms_and_condition',
                'remarks',
            );

            DB::beginTransaction();

            if(isset($request->attachment)){
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/work_cs/quotations');
                $requestData['attachment'] = $attachment;
            }
            
            $scmWcsVendor = ScmWcsVendor::create($requestData);
            $adadas = [];
            foreach ($request->scmWcsVendorServices as $key => $values) {
                $rate = $values[0]['rate'] ?? 0;
                $quantity = $values[0]['quantity'] ?? 0;


                foreach ($values as $key1 => $value) {
                    $wcsService = ScmWcsService::where([
                        'scm_wcs_id' => $scmWcs->id,
                        'scm_service_id' => $value['scm_service_id']
                    ])->first();

                    ScmWcsVendorService::create(
                        [
                            'scm_wcs_id' => $scmWcs->id,
                            'scm_wcs_vendor_id' => $scmWcsVendor->id ?? null,
                            'scm_vendor_id' => $scmWcsVendor->scm_vendor_id ?? null,
                            'scm_wcs_service_id' => $wcsService->id,
                            'scm_wr_id' => $value['scm_wr_id'] ?? null,
                            'scm_service_id' => $value['scm_service_id'] ?? null,
                            'rate' => $rate ?? null,
                            'quantity' => $quantity ?? null,
                            'quotation_ref_no' => $request->quotation_ref_no ?? null,
                            'quotation_date' =>$request->quotation_date ?? null,
                        ]
                    );
                }
            }
            DB::commit();
            return response()->success('Data created succesfully', $scmWcsVendor, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    public function showWcsQuotation($id)
    {

        $scmWcsVendor = ScmWcsVendor::with('scmWcs', 'scmVendor.scmVendorContactPerson', 'scmWcsVendorServices.scmService', 'scmWcsVendorServices.scmWr')->find($id);
        $scmWcsVendorServices = $scmWcsVendor->scmWcsVendorServices->groupBy(['scm_service_id'])->values()->all();
        data_forget($scmWcsVendor, 'scmWcsVendorServices');
        $scmWcsVendor['scmWcsVendorServices'] = $scmWcsVendorServices;
        try {
            return response()->success('Detail data', $scmWcsVendor, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    // update quotation
    public function updateWcsQuotation(ScmWcsQuotationRequest $request, $id)
    {
        try {
            // return response()->json( $request->all(), 422);
            $scmWcsVendor = ScmWcsVendor::find($id);
            $requestData = $request->only(
                'scm_vendor_id',
                'scm_wcs_id',
                'quotation_ref_no',
                'quotation_date',
                'quotation_validity',
                'payment_mode',
                'credit_term',
                'vat',
                'ait',
                'currency',
                'security_money',
                'adjustment_policy',
                'is_selected',
                'terms_and_condition',
                'remarks',
            );

            DB::beginTransaction();

            $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/work_cs/quotations', $scmWcsVendor->attachment);
            $requestData['attachment'] = $attachment;

            $scmWcsVendor->update($requestData);
            $scmWcsVendor->scmWcsVendorServices->each(function ($item) {
                $item->delete();
            });


            foreach ($request->scmWcsVendorServices as $key => $values) {
                $rate = $values[0]['rate'] ?? 0;
                $quantity = $values[0]['quantity'] ?? 0;

                foreach ($values as $key1 => $value) {
                    $wcsService = ScmWcsService::where([
                        'scm_wcs_id' => $scmWcsVendor->scm_wcs_id,
                        'scm_service_id' => $value['scm_service_id']
                    ])->first();

                    ScmWcsVendorService::create(
                        [
                            'scm_wcs_id' => $scmWcsVendor->scm_wcs_id,
                            'scm_wcs_vendor_id' => $scmWcsVendor->id ?? null,
                            'scm_vendor_id' => $scmWcsVendor->scm_vendor_id ?? null,
                            'scm_wcs_service_id' => $wcsService->id,
                            'scm_wr_id' => $value['scm_wr_id'] ?? null,
                            'scm_service_id' => $value['scm_service_id'] ?? null,
                            'rate' => $rate ?? null,
                            'quantity' => $quantity ?? null,
                            'quotation_ref_no' => $request->quotation_ref_no ?? null,
                            'quotation_date' =>$request->quotation_date ?? null,
                        ]
                    );
                }
            }
            DB::commit();
            return response()->success('Data updated succesfully', $scmWcsVendor, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    public function deleteWcsQuotation($id)
    {
        $scmWcsVendor = ScmWcsVendor::with('scmWcsVendorServices')->find($id);

        try {
            DB::beginTransaction();

            $scmWcsVendor->scmWcsVendorServices->each(function ($item) {
                $item->delete();
            });
            if(isset($scmWcsVendor->attachment)){
                $this->fileUpload->deleteFile($scmWcsVendor->attachment);
            }
            $scmWcsVendor->delete();

            DB::commit();
            return response()->success('Data deleted succesfully', null, 203);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json($scmWcsVendor->preventDeletionIfRelated(), 422);
        }
    }



        /**
     * Retrieves the data for a specific WCS ID.
     *
     * @param int $wcsId The id of the wcs.
     * @throws Some_Exception_Class If the wcsId is not found.
     * @return JsonResponse
     */
    public function getWcsData($wcsId): JsonResponse
    {
        $scmWcs = ScmWcs::query()
            ->with('scmWcsServices.scmService', 'scmWcsServices.scmWr', 'scmWarehouse')
            ->find($wcsId);

        $scmWcs['scmWcsVendor'] = ScmWcsVendor::query()
            ->with('scmVendor')
            ->where('scm_wcs_id', $wcsId)
            ->get()
            ->groupBy('scm_vendor_id');

        $scmWcs['scmWcsVendorService'] = ScmWcsVendorService::query()
            ->with('scmWcsService.scmService', 'scmWcsService.scmWr')
            ->where('scm_wcs_id', $wcsId)
            ->get()
            ->groupBy(['scm_service_id', 'scm_wr_id', 'scm_vendor_id']);


        $scmWcs['latestWoItem'] = ScmWoItem::query()
            ->with(['scmWoLine.scmWo'])
            ->whereIn('scm_service_id', $scmWcs->scmWcsServices->pluck('scm_service_id')->toArray())
            ->get()
            ->sortByDesc(function ($item) {
                return $item->scmWoLine->scmWo->date;
            })
            ->groupBy('scm_service_id')
            ->map(function ($items) {
                return $items[0];
            });


        $scmWcs['scmWcsService'] = ScmWcsService::query()
            ->with(['scmService', 'scmWr'])
            ->where('scm_wcs_id', $wcsId)
            ->get()
            ->groupBy(['scm_service_id', 'scm_wr_id'])
            ->map(function ($items) {
                return $items->map(function ($data) {
                    $data[0]['sum_quantity'] = $data->sum('quantity');
                    return $data;
                });
            });

        return response()->success('Detail data', $scmWcs, 200);
    }


    public function wcsSelectedSupplierstore(WcsSupplierSelectionRequest $request)
    {

        $data = $request->only('id', 'selection_ground', 'auditor_remarks_date', 'auditor_remarks', 'scmWcsVendor');

        try {
            $wcs = ScmWcs::find($data['id']);

            foreach ($data['scmWcsVendor'] as $key => $value) {
                if (empty($value[0]['is_selected'])) {
                    $error= [
                        'message'=>'Must be checked selected vendor.',
                        'errors'=>[
                            'service'=>['Must be checked selected vendor.']
                            ]
                        ];
                    return response()->json($error, 422);
                }
            }

            $wcs->update(
                [
                    'selection_ground' => $data['selection_ground'],
                    'auditor_remarks_date' => $data['auditor_remarks_date'],
                    'auditor_remarks' => $data['auditor_remarks'],
                ]
            );
            foreach ($data['scmWcsVendor'] as $key => $value) {
                $wcsVendor = ScmWcsVendor::find($value[0]['id']);
                $wcsVendor->update(['is_selected' => $value[0]['is_selected']]);
            }

            return response()->success('Data updated succesfully', $data, 202);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchWorkCs(Request $request)
    {
        $wcs = [];

        if (isset($request->searchParam)) {
            $wcs = ScmWcs::query()
                ->with('scmWcsVendors', 'scmWcsServices', 'scmWcsVendorServices')
                ->whereHas('scmWcsServices.scmWr', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                            ->where('business_unit', $request->business_unit)
                            ->where('scm_warehouse_id', $request->scm_warehouse_id)
                            ->where('purchase_center', $request->purchase_center);
                    });
                })
                ->orderByDesc('ref_no')
                ->get();
                
        } elseif (isset($request->scm_warehouse_id) && isset($request->purchase_center)) {
            $wcs = ScmWcs::query()
                ->with('scmWcsVendors', 'scmWcsServices', 'scmWcsVendorServices')
                ->whereHas('scmWcsServices.scmWr', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('business_unit', $request->business_unit)
                            ->where('scm_warehouse_id', $request->scm_warehouse_id)
                            ->where('purchase_center', $request->purchase_center);
                    });
                })
                ->orderByDesc('ref_no')
                ->get();
        }

        return response()->success('Search result', $wcs, 200);
    }

    public function wcsWiseVendorList(Request $request)
    {
        $wcsVendor = ScmWcsVendor::query()
            ->with('scmVendor')
            ->where('scm_wcs_id', $request->scm_wcs_id)
            ->get()
            ->map(function ($item) {
                return $item->scmVendor;
            });
        return response()->success('Search result', $wcsVendor, 200);
    }
}
