<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRINCIPAL PARTICULAR</title>
    <style>
        table, tr, td, th
        {
            border: 1px solid #252525;
            border-collapse: collapse;
        }
    </style>
</head>
<body>    
    <table>
        <tr>
            <th colspan="11" style="text-align: center; 
            font-size:20px; 
            padding:10px;"><h2>PRINCIPAL PARTICULAR @if(isset($vesselParticular))- {{$vesselParticular->opsVessel->short_code}} @endif</h2></th>
        </tr>
        @if(isset($vesselParticular))
            <tr>
                <td colspan="4">
                    <strong>BASHUNDHARA EMPRESS</strong>
                </td>
                <td colspan="7">
                    <strong>{{$vesselParticular->opsVessel->name}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    BUILT YEAR
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->year_built}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    OWNER
                </td>
                <td colspan="7">
                    {{$vesselParticular->owner_name}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    MANAGER/OPERATOR
                </td>
                <td colspan="7">
                    {{$vesselParticular->opsVessel->manager}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    CLASSIFICATION
                </td>
                <td colspan="7">
                    {{$vesselParticular->classification}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>FLAG</strong>
                </td>
                <td colspan="7">
                    <strong>{{$vesselParticular->flag}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>PORT OF REGISTRY</strong>
                </td>
                <td colspan="7">
                    <strong>{{$vesselParticular->port_of_registry}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>GROSS TONNAGE// NET TONNAGE</strong>
                </td>
                <td colspan="7">
                    <strong>{{$vesselParticular->grt}} // {{$vesselParticular->nrt}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>IMO NUMBER</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    <strong>{{$vesselParticular->imo}}</strong>                    
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    MMSI
                </td>
                <td colspan="7">
                    {{$vesselParticular->mmsi}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    OFFICIAL NUNMBER
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->official_number}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    KEEL LAYING//LAUNCHING
                </td>
                <td colspan="7">
                    {{date("d-m-Y", strtotime($vesselParticular->keel_laying_date))}}//{{date("d-m-Y", strtotime($vesselParticular->opsVessel->launching_date))}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>LENGTH OVERALL</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    <strong>{{$vesselParticular->overall_length}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    BREADTH MOULDED
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->overall_width}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    DEPTH MOULDED
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->depth_moulded}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    MAIN ENGINE
                </td>
                <td colspan="7">
                    {{$vesselParticular->engine_type}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    MAX. CONTINOUS OUTPUT, BHP
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->bhp}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    TUES CAPACITY
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->tues_capacity}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    
                </td>
                <td colspan="2">
                    
                </td>
                <td colspan="2">
                    E-MAIL:
                </td>
                <td colspan="3" style="text-align: left;">
                    {{$vesselParticular->email}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    
                </td>
                <td colspan="2">
                    
                </td>
                <td colspan="2">
                    SAT TEL:
                </td>
                <td colspan="3" style="text-align: left;">
                    
                </td>
            </tr>            
        @else
            <tr>
                <td colspan="11" style="text-align: center;">No data found...</td>
            </tr>
        @endif
    </table>
</body>
</html>