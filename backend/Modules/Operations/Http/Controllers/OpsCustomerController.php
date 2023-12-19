<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsCustomer;
use Modules\Operations\Http\Requests\OpsCustomerRequest;

class OpsCustomerController extends Controller
{
    // use HasRoles;
    
    // function __construct()
    // {
    //     $this->middleware('permission:customer-create|customer-edit|customer-show|customer-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:customer-create', ['only' => ['store']]);
    //     $this->middleware('permission:customer-edit', ['only' => ['update']]);
    //     $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {

            $customers = OpsCustomer::globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $customers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


    
    /**
     * Store a newly created resource in storage.
     * @param OpsCustomerRequest $request
     * @return JsonResponse
     */
    public function store(OpsCustomerRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $customer = OpsCustomer::create($request->all());
            DB::commit();            
            return response()->success('Data added successfully.', $customer, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified customer.
     *
     * @param  OpsCustomer  $customer
     * @return JsonResponse
     */
    public function show(OpsCustomer $customer): JsonResponse
    {
        try {
            return response()->success('Data retrieved successfully.', $customer, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsCustomerRequest  $request
     * @param  OpsCustomer  $customer
     * @return JsonResponse
     */
    public function update(OpsCustomerRequest $request, OpsCustomer $customer): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $customer->update($request->all());
            DB::commit();
            return response()->success('Data updated successfully.', $customer, 202);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsCustomer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsCustomer $customer): JsonResponse
    {
        try {
            $customer->delete($customer);

            return response()->json([
                'value' => '',
                'message' => 'Data deleted successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCustomerByNameorCode(Request $request){
        try {
            $customers = OpsCustomer::query()
            ->when(isset(request()->name_or_code), function ($query) {
                $query->where('name', 'like', '%' . request()->name_or_code . '%');
                $query->orWhere('code', 'like', '%' . request()->name_or_code . '%');
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->limit(10)
            ->get();

            return response()->success('Data retrieved successfully.', $customers, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCustomerNameorCode(Request $request){
        try {
            $customers = OpsCustomer::query()
            // ->where(function ($query) use($request) {
            //     $query->where('name', 'like', '%' . $request->name_or_code . '%');
            //     $query->orWhere('code', 'like', '%' . $request->name_or_code . '%');
            // })
            ->when(isset(request()->name_or_code), function ($query) {
                    $query->where('name', 'like', '%' . request()->name_or_code . '%');
                    $query->orWhere('code', 'like', '%' . request()->name_or_code . '%');
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->get();

            return response()->success('Data retrieved successfully.', $customers, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

}
