<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntCriticalItemCat;
use Modules\Maintenance\Http\Requests\MntCriticalItemCatRequest;

class MntCriticalItemCatController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $criticalItemCats = MntCriticalItemCat::with(['mntCriticalFunction'])->paginate(10);

            return response()->success('Critical item categories are retrieved successfully', $criticalItemCats, 200);
            
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
    public function store(MntCriticalItemCatRequest $request)
    {
        try {
            $input = $request->all();
            
            $criticalItemCat = MntCriticalItemCat::create($input);
            
            return response()->success('Critical item category created successfully', $criticalItemCat, 201);
            
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
            
            $criticalItemCat = MntCriticalItemCat::with(['mntCriticalFunction'])->find($id);
            
            return response()->success('Critical item category found successfully', $criticalItemCat, 200);
            
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
    public function update(MntCriticalItemCatRequest $request, $id)
    {
        try {
            $input = $request->all();
            
            $criticalItemCat = MntCriticalItemCat::findorfail($id);
            $criticalItemCat->update($input);
            
            return response()->success('critical item category updated successfully', $criticalItemCat, 202);
            
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
            $criticalItemCat = MntCriticalItemCat::findorfail($id);
            $criticalItemCat->delete();
            
            return response()->success('Critical item category deleted successfully', $criticalItemCat, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCriticalItemCats() {
        try {

            $criticalItemCats = MntCriticalItemCat::select("*")            
                                ->when(request()->has('mnt_critical_function_id'), function($q){
                                    $q->where('mnt_critical_function_id', request()->mnt_critical_function_id); 
                                })
                                ->get();
            return response()->success('Critical item categories are retrieved successfully', $criticalItemCats, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
