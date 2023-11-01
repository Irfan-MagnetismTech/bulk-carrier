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
use Modules\Operations\Entities\OpsCustomerInvoice;
use Modules\Operations\Entities\OpsCashRequisition;
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Entities\OpsVesselCertificate;
use Modules\Operations\Entities\OpsMaritimeCertification;
use Modules\Operations\Entities\OpsBunkerRequisition;

class OpsCommonController extends Controller
{
    // To get ports data
    public function getPortWithoutPaginate(){
        try
        {
            $ports = OpsPort::all();            
            return response()->success('Successfully retrieved ports.', $ports, 200);
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
            return response()->success('Successfully retrieved cargo types.', $cargo_types, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get cargo tariff data
    public function getCargoTariffWithoutPaginate(Request $request){
        try
        {
            $cargo_tariffs = OpsCargoTariff::with('opsVessel','opsCargoType','opsCargoTariffLines')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();

            return response()->success('Successfully retrieved cargo tariffs.', $cargo_tariffs, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Charterer Contract data
    public function getChartererContractWithoutPaginate(Request $request){
        try
        {
            $charterer_contracts = OpsChartererContract::with('opsVessel','opsChartererProfile')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();

            return response()->success('Successfully retrieved charterer contracts.', $charterer_contracts, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Charterer Invoice data
    public function getChartererInvoiceWithoutPaginate(Request $request){
        try
        {
            $charterer_invoices = OpsChartererInvoice::with('opsChartererProfile','opsChartererContract','opsChartererInvoiceLines')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->latest()->get();

            return response()->success('Successfully retrieved cargo tariffs.', $charterer_invoices, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    

    // To get Charterer Profile data
     public function getChartererProfileWithoutPaginate(Request $request){
        try
        {
            $charterer_profiles = OpsChartererProfile::with('opsChartererBankAccounts')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();         
            return response()->success('Successfully retrieved charterer profiles.', $charterer_profiles, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Customer data
    public function getCustomerWithoutPaginate(Request $request){
        try
        {
            $customers = OpsCustomer::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();  

            return response()->success('Successfully retrieved customers.', $customers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Handover Takeover data
    public function getHandoverTakeoverWithoutPaginate(Request $request){
        try
        {
            $handover_takeovers = OpsHandoverTakeover::with('opsChartererProfile','opsVessel','opsBunkers')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();  

            return response()->success('Successfully retrieved handover takeovers.', $handover_takeovers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Lighter Noon data
    public function getLighterNoonReportWithoutPaginate(Request $request){
        try
        {
            $lighterNoonReports = OpsLighterNoonReport::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();
            return response()->success('Successfully retrieved cargo tariffs.', $lighterNoonReports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Maritime Certification data
    public function getMaritimeCertificationWithoutPaginate(Request $request){
        try
        {
            $maritime_certifications = OpsMaritimeCertification::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();  
            return response()->success('Successfully retrieved maritime certifications.', $maritime_certifications, 200);
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
    public function getVesselCertificateWithoutPaginate(Request $request){
        try
        {
            $vesselCertificates = OpsVesselCertificate::with('opsVessel','opsMaritimeCertification')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->paginate(15)->groupBy('ops_vessel_id');
            
            return response()->success('Successfully retrieved vessel certificates.', $vesselCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    // To get Vessel Particular data
    public function getVesselParticularWithoutPaginate(Request $request){
        try
        {
            $vessel_particulars = OpsVesselParticular::with('ops_vessel')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();        
            return response()->success('Successfully retrieved vessel particulars.', $vessel_particulars, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Vessel Boat Note data
    public function getVoyageBoatNoteWithoutPaginate(Request $request)
    {
        try {
            $voyage_boat_notes = OpsVoyageBoatNote::with('opsVessel','opsVoyage','opsVoyageBoatNoteLines')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();
            
            return response()->success('Successfully retrieved voyage boat notes.', $voyage_boat_notes, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Voyage data
    public function getVoyageWithoutPaginate(Request $request){
        try
        {
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsMotherVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();        
            return response()->success('Successfully retrieved voyages.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get customer invoice data
    public function getCustomerInvoiceWithoutPaginate(Request $request)
    {
        try
        {
            $customerInvoices = OpsCustomerInvoice::with('opsCustomer','opsCustomerInvoiceLines')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();        
            return response()->success('Successfully retrieved customer invoices.', $customerInvoices, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Cash Requisition data
    public function getCashRequisitionWithoutPaginate(Request $request)
    {
        try
        {
            $cashRequisitions = OpsCashRequisition::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();
            return response()->success('Successfully retrieved cash requisitions.', $cashRequisitions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get bunker Requisition data
    public function getBunkerRequisitionWithoutPaginate(Request $request)
    {
        try
        {
            $bunkerRequisitions = OpsBunkerRequisition::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();        
            return response()->success('Successfully retrieved bunker requisitions.', $bunkerRequisitions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get Expense Head data
    public function getExpenseHeadWithoutPaginate(Request $request)
    {
        try
        {
            $expenseHeads = OpsExpenseHead::whereNull('head_id')->with('opsSubHeads')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()->get();        
            return response()->success('Successfully retrieved expense heads.', $expenseHeads, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // To get bulk noot report data
    public function getBulkNoonReportWithoutPaginate(Request $request): JsonResponse
    {
        try {
            $bulk_noon_reports = OpsBulkNoonReport::with(['opsVessel','opsVoyage','opsBunkers','opsBulkNoonReportPorts','opsBulkNoonReportCargoTanks','opsBulkNoonReportConsumptions','opsBulkNoonReportDistances','opsBulkNoonReportEngineInputs'])
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->get();
            
            return response()->success('Successfully retrieved bulk noon reports.', $bulk_noon_reports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
