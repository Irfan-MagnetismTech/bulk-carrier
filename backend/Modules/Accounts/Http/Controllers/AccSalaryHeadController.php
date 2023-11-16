<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccSalaryHead;

class AccSalaryHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accSalaryHeads = AccSalaryHead::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accSalaryHeads, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $accSalaryHeadData = $request->only('name', 'short_name', 'business_unit', 'type');
            $accSalaryHead     = AccSalaryHead::create($accSalaryHeadData);

            return response()->json([
                'status' => 'success',
                'value'  => $accSalaryHead,
            ], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccSalaryHead  $AccSalaryHead
     * @return \Illuminate\Http\Response
     */
    public function show(AccSalaryHead $accSalaryHead)
    {
        try {
            return response()->json([
                'status' => 'success',
                'value'  => $accSalaryHead,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccSalaryHead  $AccSalaryHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccSalaryHead $accSalaryHead)
    {
        try {
            $accSalaryHeadData = $request->only('name', 'short_name', 'business_unit', 'type');
            $accSalaryHead->update($accSalaryHeadData);

            return response()->json([
                'status' => 'success',
                'value'  => $accSalaryHead,
            ], 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccSalaryHead  $AccSalaryHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccSalaryHead $AccSalaryHead)
    {
        try {
            $AccSalaryHead->delete();

            return response()->json([
                'status' => 'success',
                'value'  => null,
            ], 204);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}