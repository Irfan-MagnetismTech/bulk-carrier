<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWcs;
use Modules\SupplyChain\Services\UniqueId;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWcsVendor;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmWcsService;
use Modules\SupplyChain\Http\Requests\ScmWcsRequest;
use Modules\SupplyChain\Entities\ScmWcsVendorService;
use Modules\SupplyChain\Http\Requests\ScmQuotationRequest;

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

    public function getWorkQuotations(Request $request)
    {
        $scmWcs = ScmWcsVendor::query()
            ->with('scmWcs', 'scmVendor.scmVendorContactPerson', 'scmWcsVendorServices')
            ->where('scm_wcs_id', $request->scm_wcs_id)
            ->get();

        return response()->success('Data list', $scmWcs, 200);
    }
    

    public function storeQuotation(ScmQuotationRequest $request)
    {
        try {
            // ScmCs::find($request->scm_cs_id)->update(['status' => 'quotation']);
            $scmWcs = ScmWcs::find($request->scm_wcs_id);
            $requestData = $request->only(
                'scm_vendor_id',
                'scm_wcs_id',
                'quotation_ref_no',
                'quotation_date',
                'attachment',
                'validity',
                'payment_mode',
                'creadit_term',
                'vat',
                'ait',
                'security_money',
                'adjustment_policy',
                'is_selected',
            );

            DB::beginTransaction();

            $scmWcsVendor = ScmWcsVendor::create($requestData);
            $adadas = [];
            foreach ($request->scmWcsVendorServices as $key => $values) {
                $rate = $values[0]['rate'] ?? 0;
                $quantity = $values[0]['quantity'] ?? 0;
                $quotation_ref_no = $values[0]['quotation_ref_no'] ?? 0;
                $quotation_date = $values[0]['quotation_date'] ?? 0;
        
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
                            'quotation_ref_no' => $quotation_ref_no ?? null,
                            'quotation_date' =>$quotation_date ?? null,,
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

}
