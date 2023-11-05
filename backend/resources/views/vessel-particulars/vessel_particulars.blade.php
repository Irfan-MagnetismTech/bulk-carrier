<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vessel Particulars</title>
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
            padding:10px;"><h2>VESSEL PARTICULARS</h2></th>
        </tr>
        <tr>
            <td colspan="4">
                <strong>BASHUNDHARA EMPRESS</strong>
            </td>
            <td colspan="7">
                <strong>{{$vesselParticulars->opsVessel->name}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>BUILT YEAR</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                <strong>{{$vesselParticulars->year_built}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>OWNER</strong>
            </td>
            <td colspan="7">
                {{$vesselParticulars->owner_name}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>MANAGER/OPERATOR</strong>
            </td>
            <td colspan="7">
                {{$vesselParticulars->opsVessel->manager}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>CLASSIFICATION</strong>
            </td>
            <td colspan="7">
                {{$vesselParticulars->classification}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>FLAG</strong>
            </td>
            <td colspan="7">
                <strong>{{$vesselParticulars->flag}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>PORT OF REGISTRY</strong>
            </td>
            <td colspan="7">
                <strong>{{$vesselParticulars->port_of_registry}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>GROSS TONNAGE// NET TONNAGE</strong>
            </td>
            <td colspan="7">
                <strong>{{$vesselParticulars->grt}} // {{$vesselParticulars->nrt}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>IMO NUMBER</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                {{$vesselParticulars->imo}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>MMSI</strong>
            </td>
            <td colspan="7">
                <strong>{{$vesselParticulars->mmsi}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>OFFICIAL NUNMBER</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                <strong>{{$vesselParticulars->official_number}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>KEEL LAYING//LAUNCHING</strong>
            </td>
            <td colspan="7">
                {{date("d-m-Y", strtotime($vesselParticulars->keel_laying_date))}}//{{date("d-m-Y", strtotime($vesselParticulars->opsVessel->launching_date))}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>LENGTH OVERALL</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                <strong>{{$vesselParticulars->overall_length}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>BREADTH MOULDED</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                {{$vesselParticulars->overall_width}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>DEPTH MOULDED</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                {{$vesselParticulars->depth_moulded}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>MAIN ENGINE</strong>
            </td>
            <td colspan="7">
                {{$vesselParticulars->engine_type}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>MAX. CONTINOUS OUTPUT, BHP</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                {{$vesselParticulars->bhp}}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>TUES CAPACITY</strong>
            </td>
            <td colspan="7" style="text-align: left;">
                {{$vesselParticulars->tues_capacity}}
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
                {{$vesselParticulars->email}}
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
    </table>
</body>
</html>