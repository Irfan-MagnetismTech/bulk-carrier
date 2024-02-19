<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Faker\Provider\ar_EG\Person;
use Modules\SupplyChain\Entities\ScmMrr;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmVendorBill;
use Modules\SupplyChain\Http\Requests\ScmVendorBillRequest;

class ScmVendorBillController extends Controller
{
    function __construct(private FileUploadService $fileUpload)
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
    public function index(Request $request): JsonResponse
    {
        try {
            $vendorBills = ScmVendorBill::with('scmVendorBillLines.scmMrr', 'scmVendorBillLines.scmPo', 'scmVendorBillLines.scmLcRecord', 'scmVendor', 'createdBy')
                ->globalSearch($request->all());

            return response()->success('Data list', $vendorBills, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmVendorBillRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no');
        $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/vbs');
        $requestData['attachment'] = $attachment;

        try {
            DB::beginTransaction();

            $scmVendorBill = ScmVendorBill::create($requestData);
            $scmVendorBill->scmVendorBillLines()->createMany($request->scmVendorBillLines);

            DB::commit();

            return response()->success('Data created succesfully', $scmVendorBill, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmVendorBill $vendorBill
     * @return JsonResponse
     */
    public function show(ScmVendorBill $vendorBill): JsonResponse
    {
        try {
            return response()->success('data', $vendorBill->load('scmWarehouse', 'scmVendorBillLines.scmMrr.scmPo', 'scmVendorBillLines.scmPo', 'scmVendorBillLines.scmLcRecord', 'scmVendor', 'createdBy'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmVendorBillRequest $request
     * @param ScmVendorBill $vendorBill
     * @return JsonResponse
     */
    public function update(ScmVendorBillRequest $request, ScmVendorBill $vendorBill): JsonResponse
    {
        $requestData = $request->except('ref_no');
        $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs', $vendorBill->attachment);
        $requestData['attachment'] = $attachment;

        try {
            DB::beginTransaction();

            $vendorBill->update($requestData);
            $vendorBill->scmVendorBillLines()->delete();
            $vendorBill->scmVendorBillLines()->createMany($request->scmVendorBillLines);

            DB::commit();

            return response()->success('Data updated sucessfully!', $vendorBill, 202);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmVendorBill $vendorBill
     * @return JsonResponse
     */
    public function destroy(ScmVendorBill $vendorBill): JsonResponse
    {
        try {
            DB::beginTransaction();

            $vendorBill->scmVendorBillLines()->delete();
            $vendorBill->delete();

            DB::commit();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVendorWiseMrr(): JsonResponse
    {
        $vendorWiseMrr = ScmMrr::query()
            ->whereHas('scmPo', function ($query) {
                $query->where('scm_vendor_id', request()->scm_vendor_id);
            })
            ->with('scmPo')
            ->get();

        return response()->success('Data retrieved successfully.', $vendorWiseMrr, 200);
    }
}
