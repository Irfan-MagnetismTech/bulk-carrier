<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntCriticalItem;
use Modules\Maintenance\Http\Requests\MntCriticalItemRequest;

class MntCriticalItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $criticalItems = MntCriticalItem::with(['mntCriticalItemCat.mntCriticalFunction'])->paginate(10);

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
            
            $criticalItem = MntCriticalItem::with(['mntCriticalItemCat'])->find($id);
            
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
    public function destroy($id)
    {
        try {            
            $criticalItem = MntCriticalItem::findorfail($id);
            $criticalItem->delete();
            
            return response()->success('Critical item deleted successfully', $criticalItem, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}