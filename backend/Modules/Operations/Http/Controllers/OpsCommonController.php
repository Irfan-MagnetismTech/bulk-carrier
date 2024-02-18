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
use Modules\Operations\Entities\OpsExpenseHead;
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
            return response()->success('Data retrieved successfully.', $ports, 200);
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
            return response()->success('Data retrieved successfully.', $cargo_types, 200);
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
            return response()->success('Data retrieved successfully.', $cargo_tariffs, 200);
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
            return response()->success('Data retrieved successfully.', $charterer_contracts, 200);
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

            return response()->success('Data retrieved successfully.', $charterer_invoices, 200);
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
            return response()->success('Data retrieved successfully.', $charterer_profiles, 200);
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
            return response()->success('Data retrieved successfully.', $customers, 200);
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
            return response()->success('Data retrieved successfully.', $handover_takeovers, 200);
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
            return response()->success('Data retrieved successfully.', $lighterNoonReports, 200);
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
            return response()->success('Data retrieved successfully.', $maritime_certifications, 200);
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
            return response()->success('Data retrieved successfully.', $vessels, 200);
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
            return response()->success('Data retrieved successfully.', $vesselCertificates, 200);
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
            return response()->success('Data retrieved successfully.', $vessel_particulars, 200);
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
            
            return response()->success('Data retrieved successfully.', $voyage_boat_notes, 200);
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
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')->latest()->get();        
            return response()->success('Data retrieved successfully.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified port.
     *
     * @param  OpsPort  $port
     * @return JsonResponse
     */
    public function getPortByID(OpsPort $port): JsonResponse
    {
        try {            
            return response()->success('Data retrieved successfully.', $port, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified port.
     *
     * @param  OpsCargoType  $cargo_type
     * @return JsonResponse
     */
    public function getCargoTypeById(OpsCargoType $cargo_type): JsonResponse
    {
        try {
            return response()->success('Data retrieved successfully.', $cargo_type, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified customer.
     *
     * @param  OpsCustomer  $customer
     * @return JsonResponse
     */
    public function getCustomerById(OpsCustomer $customer): JsonResponse
    {
        try {
            return response()->success('Data retrieved successfully.', $customer, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

        /**
     * Display the specified maritime certification.
     *
     * @param  OpsCargoTariff  $cargo_tariff
     * @return JsonResponse
     */
    public function getCargoTariffById(OpsCargoTariff $cargo_tariff): JsonResponse
    {
        $cargo_tariff->load('opsVessel','opsCargoType','opsCargoTariffLines', 'loadingPoint', 'unloadingPoint');
        try
        {
            return response()->success('Data retrieved successfully.', $cargo_tariff, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }



        /**
     * Display the specified maritime certification.
     *
     * @param  OpsVesselCertificate  $maritime_certification
     * @return JsonResponse
     */
    public function getVesselCertificateById(OpsVesselCertificate $vessel_certificate): JsonResponse
    {
        $vessel_certificate->load(['opsVessel.opsVesselCertificates'
        =>function ($query) {
            $query->whereIn('ops_vessel_certificates.id', function($query2) {
                $query2->select(DB::raw('MAX(id)'))
                    ->from('ops_vessel_certificates')
                    ->groupBy('ops_maritime_certification_id');
            })->latest();
        }, 'opsMaritimeCertification']);

        $vessel_certificate->opsVessel->opsVesselCertificates->map(function($certificate) {
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->opsMaritimeCertification->id;
            return $certificate;
        });
        
        try
        {
            return response()->success('Data retrieved successfully.', $vessel_certificate, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    /**
     * Display the specified maritime certification.
    *
    * @param  OpsVoyage  $voyage
    * @return JsonResponse
    */
    public function getVoyageById(OpsVoyage $voyage): JsonResponse
    {
    $voyage->load('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors.loadingPoint',
        'opsVoyageSectors.unloadingPoint','opsVoyagePortSchedules.portCode',
        // 'opsBunkers.scmMaterial'
    );

        $voyage->opsVoyageSectors->map(function($sector) {
            $sector->voyage_sector_id = $sector->id;
            $sector->loading_point_name_code = $sector->loadingPoint->name.'-'.$sector->loadingPoint->code;
            $sector->unloading_point_name_code = $sector->unloadingPoint->name.'-'.$sector->unloadingPoint->code;
            return $sector;
        });

        try
        {
            return response()->success('Data retrieved successfully.', $voyage, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


        /**
     * Display the specified maritime certification.
    *
    * @param  OpsExpenseHead  $expense_head
    * @return JsonResponse
    */
    public function getExpenseHeadById(OpsExpenseHead $expense_head): JsonResponse
    {
    try
    {
        return response()->success('Data retrieved successfully.', $expense_head->load('opsSubHeads'), 200);
    }
    catch (QueryException $e)
    {
        return response()->error($e->getMessage(), 500);
    }

    }

}
