<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Entities\OpsCargoTariff;
use Modules\Operations\Http\Requests\OpsPortRequest;
use Illuminate\Support\Facades\DB;

class OpsCommonController extends Controller
{
    // To get ports data
    public function getPortWithoutPaginate(){
        try
        {
            $ports = OpsPort::all();            
            return response()->success('Successfully retrieved ports for without paginate.', $ports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get cargo type data
    public function getCargoTypeWithoutPaginate(){
        try
        {
            $cargo_types = OpsCargoType::all();
            return response()->success('Successfully retrieved cargo types for without paginate.', $cargo_types, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get cargo tariff data
    public function getCargoTariffWithoutPaginate(){
        try
        {
            $cargo_tariffs = OpsCargoTariff::with('opsVessel','opsCargoType','opsCargoTariffLines')->latest()->get();          
            return response()->success('Successfully retrieved cargo tariffs for without paginate.', $cargo_tariffs, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
