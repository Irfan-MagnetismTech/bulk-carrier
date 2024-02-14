<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWcs;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWcsVendor;
use Modules\SupplyChain\Entities\ScmWcsService;
use Modules\SupplyChain\Entities\ScmWcsVendorService;
use Modules\SupplyChain\Http\Requests\ScmWcsQuotationRequest;
use Modules\SupplyChain\Http\Requests\WcsSupplierSelectionRequest;

class ScmWcsqController extends Controller
{
    function __construct(private FileUploadService $fileUpload)
    {
            // $this->middleware('permission:work-cs-quotation-create|work-cs-quotation-edit|work-cs-quotation-show|work-cs-quotation-delete', ['only' => ['getWcsQuotations','showWcsQuotation']]);
            // $this->middleware('permission:work-cs-quotation-create', ['only' => ['storeWcsQuotation','wcsSelectedSupplierstore']]);
            // $this->middleware('permission:work-cs-quotation-edit', ['only' => ['updateWcsQuotation','wcsSelectedSupplierstore']]);
            // $this->middleware('permission:work-cs-quotation-delete', ['only' => ['deleteWcsQuotation']]);
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

            return response()->error($e->getMessage(), 500);
        }
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
}
