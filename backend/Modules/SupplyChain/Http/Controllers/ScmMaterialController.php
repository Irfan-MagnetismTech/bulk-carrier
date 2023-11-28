<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Exports\ScmMaterialsExport;
use App\Imports\ScmMaterialsImport;
use App\Services\FileUploadService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Entities\ScmMaterialCategory;
use Modules\SupplyChain\Http\Requests\ScmMaterialRequest;

class ScmMaterialController extends Controller
{
    // use HasRoles;

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
    public function index(): JsonResponse
    {
        try {
            $scm_material_categories = ScmMaterial::with('scmMaterialCategory')
                ->globalSearch(request()->all());

            return response()->success('Material Category list', $scm_material_categories, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmMaterialRequest $request): JsonResponse
    {
        $requestData = $request->all();
        try {
            if (isset($request->sample_photo)) {
                $sample_photos = $this->fileUpload->handleFile($request->sample_photo, 'scm/materials');
                $requestData['sample_photo'] = $sample_photos;
            }
            $material = ScmMaterial::create($requestData);
            // scmMaterlCategory getTopLevelParent
            // $data = ScmMaterialCategory::topLevelParent($request->scm_material_category_id);
            // return response()->json(['message' => 'Data created successfully', 'data' => $data], 422);
            // $material->account()->create([
            //     'acc_balance_and_income_line_id' => config('accounts.balance_income_line.inventory'),
            //     'account_name' => $material->name,
            //     'account_code' => "config('accounts.account_types.Assets') - 5 - config('accounts.balance_income_line.inventory') - $material->id",
            //     'account_type' => config('accounts.account_types.Assets'),
            //     'business_unit' => 'PSML',
            // ]);
            return response()->success('Data created succesfully', $material, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMaterial $material
     * @return JsonResponse
     */
    public function show(ScmMaterial $material): JsonResponse
    {
        try {
            return response()->success('data', $material->load('scmMaterialCategory'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMaterialRequest $request
     * @param ScmMaterial $material
     * @return JsonResponse
     */
    public function update(ScmMaterialRequest $request, ScmMaterial $material): JsonResponse
    {
        $requestData = $request->all();

        try {
            if (isset($request->sample_photo)) {
                $sample_photos = $this->fileUpload->handleFile($request->sample_photo, 'scm/materials', $material->sample_photo);
                $requestData['sample_photo'] = $sample_photos;
            }
            $material->update($requestData);

            return response()->success('Data updated sucessfully!', $material, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMaterial $material
     * @return JsonResponse
     */
    public function destroy(ScmMaterial $material): JsonResponse
    {
        try {
            $this->fileUpload->deleteFile($material->sample_photo);
            $material->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchMaterial(): JsonResponse
    {
        $materialCategory = ScmMaterial::query()
            ->when(request()->has('materialCategoryId'), function ($query) {
                $query->whereScmMaterialCategoryId(request()->materialCategoryId);
            })
            // ->where(function ($query) {
            //     $query->where('name', 'like', "%" . request()->searchParam . "%")
            //         ->orWhere('material_code', 'like', "%" . request()->searchParam . "%");
            // })
            ->orderByDesc('name')
            //->limit(10)
            ->get();

        return response()->success('Search result', $materialCategory, 200);
    }

    public function excelImport(Request $request)
    {
        try {

            $import = new ScmMaterialsImport();
            Excel::import($import, $request->file);
            ob_end_clean();

            if ($import->invalid) {
                return redirect()->back()->withInput()->withErrors($import->invalid);
            }
            $notification = array(
                'messege' => 'product Uploaded Successfully',
                'alert-type' => 'success',
            );
            return redirect()->route('products.index')->with($notification);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }

            return redirect()->back()->withInput()->withErrors($failure);
        }
    }

    public function export()
    {
        return Excel::download(new ScmMaterialsExport, 'materials.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
