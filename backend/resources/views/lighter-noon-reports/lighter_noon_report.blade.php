<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lighter Noon Report</title>
</head>
<style>
    .title{
        text-align: center; 
        font-size:20px; 
        padding:10px;
    }
    /* th{
        background-color: blue; 
        color:white; 
        white-space: normal; 
        text-align: center;
    } */
</style>
<body>    
    <table>
        <tr>
            <td colspan="13" style="text-align: center; 
            font-size:20px; 
            padding:10px;"><h2>DAILY STATUS OF {{(($lighter_noon_reports)? ($lighter_noon_reports['vessel_name']): 'Toggi Shipping')}}</h2></td>
        </tr>
        <tr>
            <td colspan="13" style="text-align: center;">{{ html_entity_decode($companyName, ENT_QUOTES, 'UTF-8') }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">DATE</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">SHIP NAME</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">TIME</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">CARGO TYPE</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">LOADING PLACE</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">UNLOADING PLACE</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">NOON POSITION</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">STATUS</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">FUEL BUNKERING</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">ENGINE RUNNING HOURS</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">FUEL-CON/24H</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">FUEL-STOCK/L</th>
            <th style="background-color: blue; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">REMARKS</th>
        </tr>
        @if(isset($lighter_noon_reports['data']))
            @foreach ($lighter_noon_reports['data'] as $lighter_noon_report)
                <tr>
                    <td>{{date("d-M-Y", strtotime($lighter_noon_report?->date))}}</td>
                    <td>{{ $lighter_noon_report?->opsVessel?->name}} </td>
                    <td>{{date("g:i A", strtotime($lighter_noon_report?->date))}}</td>
                    <td>{{ $lighter_noon_report?->opsVoyage?->opsCargoType?->cargo_type}} </td>
                    <td>{{ $lighter_noon_report?->last_port}} </td>
                    <td>{{ $lighter_noon_report?->next_port}} </td>
                    <td>{{ $lighter_noon_report?->noon_position}} </td>
                    <td>{{ $lighter_noon_report?->status}} </td>
                    <td>{{ $lighter_noon_report?->bunkering}} </td>
                    <td>{{ $lighter_noon_report?->engine_running_hours}} </td>
                    <td>{{ $lighter_noon_report?->fuel_con_24h}} </td>
                    <td>{{ $lighter_noon_report?->fuel_stock_l}} </td>
                    <td>{{ $lighter_noon_report?->remarks}} </td>                    
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="13" style="text-align: center;">No data found...</td>
            </tr>
        @endif
    </table>
</body>
</html>