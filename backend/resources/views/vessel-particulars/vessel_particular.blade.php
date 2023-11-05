<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vessel Particular @if(isset($vesselParticular))- {{$vesselParticular->opsVessel->short_code}} @endif</title>
</head>
<style>
    .title{
        text-align: center; 
        font-size:20px; 
        padding:10px;
    }
    .float-left{
        float: left !important;
    }
</style>
<body>
    
    <table>
        <tr rowspan="3">
            <th colspan="11" style="text-align: center; 
            font-size:20px; 
            padding:10px;"><h2>VESSEL PARTICULAR @if(isset($vesselParticular))- {{$vesselParticular->opsVessel->short_code}} @endif</h2></th>
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
                    <strong>BUILT YEAR</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    <strong>{{$vesselParticular->year_built}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>OWNER</strong>
                </td>
                <td colspan="7">
                    {{$vesselParticular->owner_name}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>MANAGER/OPERATOR</strong>
                </td>
                <td colspan="7">
                    {{$vesselParticular->opsVessel->manager}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>CLASSIFICATION</strong>
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
                    {{$vesselParticular->imo}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>MMSI</strong>
                </td>
                <td colspan="7">
                    <strong>{{$vesselParticular->mmsi}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>OFFICIAL NUNMBER</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    <strong>{{$vesselParticular->official_number}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>KEEL LAYING//LAUNCHING</strong>
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
                    <strong>BREADTH MOULDED</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->overall_width}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>DEPTH MOULDED</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->depth_moulded}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>MAIN ENGINE</strong>
                </td>
                <td colspan="7">
                    {{$vesselParticular->engine_type}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>MAX. CONTINOUS OUTPUT, BHP</strong>
                </td>
                <td colspan="7" style="text-align: left;">
                    {{$vesselParticular->bhp}}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>TUES CAPACITY</strong>
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
                    <strong>E-MAIL: </strong>
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
                    <strong>SAT TEL: </strong>
                </td>
                <td colspan="3" style="text-align: left;">
                    <strong></strong>
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