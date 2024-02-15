<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntCriticalItem;
use Modules\Maintenance\Entities\MntCriticalVesselItem;
use Modules\Maintenance\Http\Requests\MntCriticalItemRequest;

class MntCriticalItemController extends Controller
{
    use HasRoles; 

    public function __construct()
    {
        $this->middleware('permission:mnt-critical-item-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:mnt-critical-item-create', ['only' => ['store']]);
        $this->middleware('permission:mnt-critical-item-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:mnt-critical-item-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $criticalItems = MntCriticalItem::with(['mntCriticalItemCat.mntCriticalFunction'])->globalSearch($request->all());

            return response()->success('Critical items are retrieved successfully', $criticalItems, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('maintenance::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MntCriticalItemRequest $request)
    {
        try {
            $input = $request->all();
            
            $criticalItem = MntCriticalItem::create($input);
            
            return response()->success('Critical item created successfully', $criticalItem, 201);
            
        }
        catch (\Exception $e)
        {
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
            
            $criticalItem = MntCriticalItem::with(['mntCriticalItemCat.mntCriticalFunction.mntCriticalItemCats'])->find($id);
            
            return response()->success('Critical item found successfully', $criticalItem, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('maintenance::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(MntCriticalItemRequest $request, $id)
    {
        try {
            $input = $request->all();
            
            $criticalItem = MntCriticalItem::findorfail($id);
            $criticalItem->update($input);
            
            return response()->success('Critical item updated successfully', $criticalItem, 202);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(MntCriticalItem $criticalItem)
    {
        try {
            DB::beginTransaction();
            $criticalItem->delete();
            DB::commit();
            return response()->success('Critical item deleted successfully', $criticalItem, 204);
            
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($criticalItem->preventDeletionIfRelated(), 422);

        }
    }

    public function getCriticalItems() {
        try {

            $criticalItems = MntCriticalItem::select("*")            
                                ->when(request()->has('mnt_critical_item_cat_id'), function($q){
                                    $q->where('mnt_critical_item_cat_id', request()->mnt_critical_item_cat_id); 
                                })
                                ->get();

            return response()->success('Critical items are retrieved successfully', $criticalItems, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
