<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voyage Details</title>
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
    <?php
        $total_cargo_ton= 0;
        $total_cargo_rate= 0;
        $total_unloading_rate= 0;
        $total_line_cost= 0;
        $total_other_cost= 0;
        $total_line_fuel= 0;
        $total_u_fuel= 0;
        $total_bunkering= 0;
        $total_consume= 0;
    ?>
    <table>
        <tr>
            <td colspan="3" rowspan="3"></td>
            <td colspan="11" style="text-align: center; 
            font-size:20px; 
            padding:10px;"><h2>DAILY STATUS {{(($voyage_reports['vessel_name'])? 'OF '. (ucwords($voyage_reports['vessel_name'])): 'OF TOGGI SHIPPING')}}</h2></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center;">{{ html_entity_decode($companyName, ENT_QUOTES, 'UTF-8') }}</td>
            <td style="text-align: right;">Date:</td>
            <td colspan="2">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="11"></td>
            <td style="text-align: right;">Time:</td>
            <td colspan="2">{{ now()->format('g:i A') }}</td>
        </tr>

        <tr>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">DATE</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">VOYAGE NAME</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">NAME OF SHIP</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">LOADING PLACE</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">UNLOADING PLACE</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">CARGO TYPE</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">CARGO/TON</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">CARGO/RATE</td>
            <td rowspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">UNLOADING/RATE</td>
            <td colspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">PRETTY CASH/TAKA</td>
            
            <td style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">HP</td>
            <td colspan="2" style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">FUEL CONSUME</td>
            <td style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">BUNKERING</td>
            <td style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">CONSUME</td>
            <td style="background-color: #969695; color:white; white-space: normal; word-wrap: break-word; text-align: center; vertical-align: middle;">STOCK</td>
        </tr>
        <tr>
            <td style="background-color: #f4f6f4; color:#ed811d;">Line Cost</td>
            <td style="background-color: #f4f6f4; color:#ed811d;">Others Cost</td>
            <td style="background-color: #f4f6f4; color:#ed811d;">Total Taka</td>
            <td style="background-color: #f4f6f4; color:#ed811d;">LINE FUEL</td>
            <td style="background-color: #f4f6f4; color:#ed811d;">U.FUEL</td>
            <td style="background-color: #5849FC; color:#2B2A2A;">30000</td>
            <td style="background-color: #5849FC; color:#2B2A2A;">30000</td>
            <td style="background-color: #5849FC; color:#2B2A2A;">30000</td>
        </tr>

        @if(count($voyage_reports['data']) > 0)
            @foreach($voyage_reports['data'] as $voyage_report)
                <?php
                    $total_cargo_ton += collect($voyage_report->opsVoyageSectors)->sum('latest_qty');
                    $total_cargo_rate += collect($voyage_report->opsVoyageSectors)->sum('rate')/count($voyage_report->opsVoyageSectors);
                    $total_unloading_rate += collect($voyage_report->opsVoyageSectors)->sum('rate')/count($voyage_report->opsVoyageSectors);
                ?>
                <tr>
                    <td>{{date("d-M-Y", strtotime($voyage_report?->created_at))}}</td>
                    <td>{{$voyage_report?->voyage_no}}</td>
                    <td>{{$voyage_report?->opsVessel?->name}}</td>
                    <td>{{$voyage_report?->opsVessel?->name}}</td>
                    <td>{{$voyage_report?->opsVessel?->name}}</td>
                    <td>{{$voyage_report->opsCargoType?->cargo_type}}</td>
                    <td>{{($voyage_report->opsVoyageSectors)? $voyage_report->opsVoyageSectors->sum('latest_qty'): '00'}}</td>
                    <td>{{($voyage_report->opsVoyageSectors)? $voyage_report->opsVoyageSectors->sum('rate')/count($voyage_report->opsVoyageSectors): '00'}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"></td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_cargo_ton, 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_cargo_rate, 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_unloading_rate, 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_line_cost, 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_other_cost, 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format(($total_other_cost + $total_line_cost), 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_line_fuel, 2) }}</td>
                <td style="background-color: yellow; color:#1c1c1c;">{{ number_format($total_u_fuel, 2) }}</td>           
                <td style="background-color: yellow; color:#1c1c1c;"></td>
                <td style="background-color: yellow; color:#1c1c1c;"></td>
                <td style="background-color: yellow; color:#1c1c1c;"></td>
            </tr>
        @else
            <tr>
                <td colspan="17" style="text-align: center;">No Data Found...</td>
            </tr>
        @endif
    </table>
</body>
</html>