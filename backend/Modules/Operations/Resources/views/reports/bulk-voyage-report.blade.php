<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bulk Voyage Report</title>
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    table th, table td {
        border: 1px solid #000;
        padding: 5px;
    }
    table th {
        background-color: #ccc;
        text-align: left;
    }
    .table_css th {
        max-width: 30px;
    }
    .sub_table_css th {
        text-align: center;
    }
    .table_css td {
        text-align: left;
    }
</style>
<body>
    <table>
        <thead>
            <tr>
                <td colspan="3" style="text-align: center; 
                font-size:24px;
                padding:2px;"><h2>Bulk Voyage Report</h2></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center;">{{ html_entity_decode($data['companyName'], ENT_QUOTES, 'UTF-8') }}</td>
            </tr>
            <tr>
                <td style="width:50%;"></td>
                <td style="text-align: right; width:30%;">Date:</td>
                <td>{{ now()->format('d/M/Y') }}</td>
            </tr>
            <tr>
                <td style="width:50%;"></td>
                <td style="text-align: right; width:30%;">Time:</td>
                <td>{{ now()->format('g:i A') }}</td>
            </tr>

        </thead>
    </table>

    <table class="table_css">
        <tbody>
            <h2 style="margin-bottom: -2px; margin-top: 10px; font-size:20px; font:bold;">Basic Information</h2>
            <tr>
                <th colspan="2">Business Unit</th>
                <td colspan="8">{{ $data['bulk_noon_report']->business_unit}}</td>
            </tr>
            <tr>
                <th colspan="2">Date & Time</th>
                <td colspan="8">{{ \Carbon\Carbon::parse($data['bulk_noon_report']->date_time)->format('d/m/Y \a\t h:i A') }}</td>
            </tr>
            <tr>
                <th colspan="2">Vessel</th>
                <td colspan="8">{{ $data['bulk_noon_report']->opsVessel->name}}</td>
            </tr>
            <tr>
                <th colspan="2">Voyage</th>
                <td colspan="8">{{ $data['bulk_noon_report']->opsVoyage->voyage_no}}</td>
            </tr>
            <tr>
                <th colspan="2">Ship Master</th>
                <td colspan="8">{{ $data['bulk_noon_report']->ship_master}}</td>
            </tr>
            <tr>
                <th colspan="2">Chief Engineer</th>
                <td colspan="8">{{ $data['bulk_noon_report']->chief_engineer}}</td>               
            </tr>
            <tr>
                <th colspan="2">Noon Position</th>
                <td colspan="8">{{ $data['bulk_noon_report']->noon_position}}</td>               
            </tr>
            <tr>
                <th colspan="2">Engine Running Hours</th>
                <td colspan="8">{{ $data['bulk_noon_report']->engine_running_hours}}</td>               
            </tr>
            <tr>
                <th colspan="2">Lat/Long</th>
                <td colspan="8">{{ $data['bulk_noon_report']->lat_long}}</td>               
            </tr>
            <tr>
                <th colspan="2">Wind Condition</th>
                <td colspan="8">{{ $data['bulk_noon_report']->wind_condition}}</td>               
            </tr>
            <tr>
                <th colspan="2">Remarks</th>
                <td colspan="8">{{ $data['bulk_noon_report']->remarks}}</td>               
            </tr>
            <tr>
                <th colspan="2">Status</th>
                <td colspan="8">{{ $data['bulk_noon_report']->status}}</td>               
            </tr>
        </tbody>
    </table>

    <table style="margin-top: 12px" class="table_css">
        <tbody>
            @if(count($data['bulk_noon_report']->opsBulkNoonReportPorts))
                <h2 style="margin-bottom: -10px; margin-top: 10px; font-size:20px; font:bold;">Upcoming Port</h2>
                @foreach($data['bulk_noon_report']->opsBulkNoonReportPorts as $port)
                    <tr>
                        <th colspan="2">Last Port:</th>
                        <td colspan="4">{{ $port->last_port ? $port->last_port :'' }}</td>
                        <th colspan="2">Next Port:</th>
                        <td colspan="4">{{ $port->next_port ? $port->next_port :'' }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">ETA:</th>
                        <td colspan="2">{{ $port->eta ? $port->eta :'' }}</td>
                        <th colspan="2">Distance Run:</th>
                        <td colspan="2">{{ $port->distance_run ? $port->distance_run :'' }}</td>
                        <th colspan="2">DTG:</th>
                        <td colspan="2">{{ $port->dtg ? $port->dtg :'' }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Remarks</th>
                        <td colspan="10"></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <table style="margin-top: 12px" class="table_css">
        <tbody>
            @if(isset($data['bulk_noon_report']->opsBulkNoonReportDistance))
                <h2 style="margin-bottom: -10px; margin-top: 10px; font-size:20px; font:bold;">Distance and Vessel</h2>
                <tr>
                    <th>CP/Ordered Speed:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->cp_ordered_speed }}</td>
                    <th>Average RPM:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->average_rpm }}</td>
                    <th>Reported Speed:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->reported_speed }}</td>
                    <th>Fwd Draft:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->fwd_draft }}</td>
                  </tr>
                  <tr>
                    <th>Observed Distance:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->observed_distance }}</td>
                    <th>Mild Draft:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->mild_draft }}</td>
                    <th>Engine Distance:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->engine_distance }}</td>
                    <th>Aft Draft:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->aft_draft }}</td>
                  </tr>
                  <tr>
                  </tr>
                  <tr>
                    <th>Main Engine Revs:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->main_engine_revs }}</td>
                    <th>Heading:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->heading }}</td>
                    <th>Slip %:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->slip_percent }}</td>
                    <th>Steaming Hours:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->steaming_hours }}</td>
                  </tr>
                  <tr>
                    <th>Salinity:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->salinity}}</td>
                    <th>S. DWT:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->s_dwt }}</td>
                    <th>Ballast:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->ballast }}</td>
                    <th>S. Displacement:</th>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportDistance->s_displacement }}</td>
                  </tr> 
                  <tr>
                    <th>FW Last Day Noon ROB:</th>
                    <td>{{ $data['bulk_noon_report']->fw_last_day_noon_rob }}</td>
                    <th>FW Production:</th>
                    <td>{{ $data['bulk_noon_report']->fw_production }}</td>
                    <th>FW Consumption:</th>
                    <td>{{ $data['bulk_noon_report']->fw_consumption }}</td>
                    <th>FW Today Noon ROB:</th>
                    <td>{{ $data['bulk_noon_report']->fw_today_noon_rob }}</td>
                  </tr>
                  @if(count($data['bulk_noon_report']->opsBulkNoonReportCargoTanks))
                  <tr>
                    <table style="margin-top: 10px"  class="sub_table_css">
                        <tr>
                        <th>Cargo Tanks</th>
                        <th>Liq Level</th>
                        <th>Pressure</th>
                        <th>Vapor Temp</th>
                        <th>Liq Temp</th>
                        <th>Quantity (MT)</th>
                        </tr>
                        <tbody>
                        @foreach($data['bulk_noon_report']->opsBulkNoonReportCargoTanks as $index=>$tank)
                            <tr>
                                <td>{{ 'CT' . ($index+1) }}</td>
                                <td>{{ $data['bulk_noon_report']->opsBulkNoonReportCargoTanks[$index]?->liq_level }}</td>
                                <td>{{ $data['bulk_noon_report']->opsBulkNoonReportCargoTanks[$index]?->pressure }}</td>
                                <td>{{ $data['bulk_noon_report']->opsBulkNoonReportCargoTanks[$index]?->vapor_temp }}</td>
                                <td>{{ $data['bulk_noon_report']->opsBulkNoonReportCargoTanks[$index]?->liq_temp }}</td>
                                <td>{{ $data['bulk_noon_report']->opsBulkNoonReportCargoTanks[$index]?->quantity_mt }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                  </tr>
                  @endif
            @endif
        </tbody>
    </table>
    @if(count($data['bulk_noon_report']->opsBulkNoonReportConsumptions))
        <table style="margin-top: 12px" class="sub_table_css">
            <h2 style="margin-bottom: -10px; margin-top: 10px; font-size:20px; font:bold;">Bunker Consumptions</h2>
            <tr>
                <th class="w-40">Type</th>
                <th class="w-40">Previous ROB</th>
                <th class="w-40">Received</th>
                <th class="w-40">Consumption</th>
                <th class="w-40">ROB</th>
            </tr>
            <tbody>
                @foreach($data['bulk_noon_report']->opsBulkNoonReportConsumptions as $index=>$consumption)
                    <tr>
                        <td>{{ $data['bulk_noon_report']->opsBulkNoonReportConsumptions[$index]?->scmMaterial?->name }}</td>
                        <td>{{ $data['bulk_noon_report']->opsBulkNoonReportConsumptions[$index]?->previous_rob }}</td>
                        <td>{{ $data['bulk_noon_report']->opsBulkNoonReportConsumptions[$index]?->received }}</td>
                        <td>{{ $data['bulk_noon_report']->opsBulkNoonReportConsumptions[$index]?->consumption }}</td>
                        <td>{{ $data['bulk_noon_report']->opsBulkNoonReportConsumptions[$index]?->rob }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if(count($data['bulk_noon_report']->opsBulkNoonReportEngineInputs))
        <table style="margin-top: 12px" class="sub_table_css">
            <h2 style="margin-bottom: -10px; margin-top: 10px; font-size:20px; font:bold;">Engine Info</h2>
            <tr>
                <th class="w-40">Unit </th>
                <th class="w-40">PCO</th>
                <th class="w-40">Rack</th>
                <th class="w-40">Exh. Temp.</th>
              </tr>
              <tbody>
                @foreach($data['bulk_noon_report']->opsBulkNoonReportEngineInputs as $index=>$engine)
                    <tr v-for="(consumption, index) in bulkNoonReport.opsBulkNoonReportEngineInputs">
                    <td>{{ ($data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->type == 'engine_unit')? "Engine Unit" : $data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->type}}<span style="margin-left: 5px"></span>{{ ($data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->engine_unit)?$data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->engine_unit : null }}</td>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->pco }}</td>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->rack }}</td>
                    <td>{{ $data['bulk_noon_report']->opsBulkNoonReportEngineInputs[$index]?->exh_temp }}</td>
                    </tr>
                @endforeach
              </tbody>
        </table>
    @endif
</body>
</html>