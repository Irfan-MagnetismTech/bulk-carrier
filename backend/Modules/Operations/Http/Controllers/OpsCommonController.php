<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsCustomer;
use Modules\Operations\Entities\OpsCargoType;
use Modules\Operations\Entities\OpsCargoTariff;
use Modules\Operations\Entities\OpsVoyageBoatNote;
use Modules\Operations\Entities\OpsChartererInvoice;
use Modules\Operations\Entities\OpsChartererProfile;
use Modules\Operations\Entities\OpsHandoverTakeover;
use Modules\Operations\Entities\OpsVesselParticular;
use Modules\Operations\Http\Requests\OpsPortRequest;
use Modules\Operations\Entities\OpsChartererContract;
use Modules\Operations\Entities\OpsLighterNoonReport;

use Modules\Operations\Entities\OpsVesselCertificate;
use Modules\Operations\Entities\OpsMaritimeCertification;

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

    // To get Charterer Contract data
    public function getChartererContractWithoutPaginate(){
        try
        {
            $charterer_contracts = OpsChartererContract::with('opsVessel','opsChartererProfile')->latest()->get();        
            return response()->success('Successfully retrieved charterer contracts for without paginate.', $charterer_contracts, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Charterer Invoice data
    public function getChartererInvoiceWithoutPaginate(){
        try
        {
            $charterer_invoices = OpsChartererInvoice::with('opsChartererProfile','opsChartererContract','opsChartererInvoiceLines')->latest()->get();

            return response()->success('Successfully retrieved cargo tariffs for without paginate.', $charterer_invoices, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    

    // To get Charterer Profile data
     public function getCargoProfileWithoutPaginate(){
        try
        {
            $charterer_profiles = OpsChartererProfile::with('opsChartererBankAccounts')->latest()->get();       
            return response()->success('Successfully retrieved charterer profiles for without paginate.', $charterer_profiles, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Customer data
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

    // To get Handover Takeover data
    public function getHandoverTakeoverWithoutPaginate(){
        try
        {
            $handover_takeovers = OpsHandoverTakeover::with('opsChartererProfile','opsVessel','opsBunkers')->latest()->get();        
            return response()->success('Successfully retrieved handover takeovers for without paginate.', $handover_takeovers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Lighter Noon data
    public function getLighterNoonReportWithoutPaginate(){
        try
        {
            $lighterNoonReports = OpsLighterNoonReport::all();            
            return response()->success('Successfully retrieved cargo tariffs for without paginate.', $lighterNoonReports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Maritime Certification data
    public function getMaritimeCertificationWithoutPaginate(){
        try
        {
            $maritime_certifications = OpsMaritimeCertification::all();            
            return response()->success('Successfully retrieved maritime certifications for without paginate.', $maritime_certifications, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Vessel data
    public function getVesselWithoutPaginate(Request $request){
        try
        {
            $vessels = OpsVessel::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();            
            return response()->success('Successfully retrieved vessels.', $vessels, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Vessel Certificate data
    public function getVesselCertificateWithoutPaginate(){
        try
        {
            $vesselCertificates = OpsVesselCertificate::with('opsVessel','opsMaritimeCertification')->latest()->paginate(15);
            return response()->success('Successfully retrieved vessel certificates for without paginate.', $vesselCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Vessel Particular data
    public function getVesselParticularWithoutPaginate(){
        try
        {
            $vessel_particulars = OpsVesselParticular::with('ops_vessel')->latest()->get();        
            return response()->success('Successfully retrieved vessel particulars for without paginate.', $vessel_particulars, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Vessel Boat Note data
    public function getVoyageBoatNoteWithoutPaginate()
    {
        try {
            $voyage_boat_notes = OpsVoyageBoatNote::with('opsVessel','opsVoyage','opsVoyageBoatNoteLines')->latest()->get();
            
            return response()->success('Successfully retrieved voyage boat notes for without paginate.', $voyage_boat_notes, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Voyage data
    public function getVoyageWithoutPaginate(){
        try
        {
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsMotherVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')->latest()->get();        
            return response()->success('Successfully retrieved voyages for without paginate.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
