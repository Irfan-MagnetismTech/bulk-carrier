<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsCustomer;
use Modules\Operations\Http\Requests\OpsCustomerRequest;
use Illuminate\Support\Facades\DB;

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
            $customers = OpsCustomer::latest()->paginate(15);
            return response()->success('Successfully retrieved customers.', $customers, 200);
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
            return response()->success('Customer added Successfully.', $customer, 201);
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
            return response()->success('Successfully retrieved customer.', $customer, 200);
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
            return response()->success('Customer updated Successfully.', $customer, 202);
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
                'message' => 'Customer deleted Successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCustomerName(){
        try {
            $customers = OpsCustomer::all();
            return response()->success('Successfully retrieved customers name.', collect($customers->pluck('name'))->unique()->values()->all(), 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCustomerWithoutPaginate(){
        try
        {
            $customers = OpsCustomer::all();            
            return response()->success('Successfully retrieved customers for without paginate.', $customers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
