<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voyage Report</title>
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
        {{-- <tr>
            <th colspan="11" style="text-align: center; 
            font-size:20px;
            padding:10px;"><h2>Voyage Report</h2></th>
        </tr> --}}
        {{-- <tr>
            <td colspan="11" style="text-align: center;">{{ html_entity_decode($companyName, ENT_QUOTES, 'UTF-8') }}</td>
        </tr> --}}
        {{-- <tr>
            <td colspan="9" style="text-align: right;">Date:</td>
            <td colspan="2">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: right;">Time:</td>
            <td colspan="2">{{ now()->format('g:i A') }}</td>
        </tr> --}}
        {{-- @dd($data['voyages']) --}}
        @if(isset($data['voyages']))
        <tr>
            <th>DATE</th>
            <th>VOAYAGE NO.</th>
            <th>NAME OF SHIP</th>
            <th>LOADING POINT</th>
            <th>UNLOADING POINT</th>
            <th>CARGO TYPE</th>
            <th>CARGO/TON</th>
            @foreach($data['opsContractTitle'] as $title)
                <th>{{ strtoupper($title['particular']) }}</th>
            @endforeach
            @foreach($data['opsExpenditureHeadTitle'] as $title)
                <th>{{ strtoupper($title['name']) }}</th>
            @endforeach
        </tr>          
        @foreach ($data['voyages'] as $key => $voyage)   
            {{-- @dd(count($voyage['opsVoyageSectors'])>1) --}}
            @foreach($voyage['opsVoyageSectors'] as $key1=>$sector)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{$voyage['sail_date']}}</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{$voyage['voyage_no']}}</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{$voyage['opsVessel']['name']}}</td>
                    @endif
                    <td>{{ $sector['loading_point'] }}</td>
                    <td>{{ $sector['unloading_point'] }}</td>
                    @if($loop->first)
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{$voyage['opsCargoType']['cargo_type']}}</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{$sector['quantity']?$sector['quantity']:'00'}}</td>
                        {{-- <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{ $voyage['opsContractTariffs'][0]['FREIGHT RATE']?$voyage['opsContractTariffs'][0]['FREIGHT RATE']:'00' }}</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">{{ $voyage['opsContractTariffs'][0]['UNLOADING RATE']?$voyage['opsContractTariffs'][0]['UNLOADING RATE']:'00' }}</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">fgfdg</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">gfg</td> --}}
                    @endif
                    @foreach($voyage['opsContractTariffs'] as $key1=>$tariff)
                        <td rowspan="{{count($voyage['opsContractTariffs'])}}">{{ $tariff['FREIGHT RATE']?$tariff['FREIGHT RATE']:'00' }}</td>
                        <td rowspan="{{count($voyage['opsContractTariffs'])}}">{{ $tariff['UNLOADING RATE']?$tariff['UNLOADING RATE']:'00' }}</td>
                    @endforeach
                    @if($loop->first)
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">fgfdg</td>
                        <td rowspan="{{(count($voyage['opsVoyageSectors'])>count($voyage['opsContractTariffs'])? count($voyage['opsVoyageSectors']) : count($voyage['opsContractTariffs']))}}">gfg</td>
                    @endif
                </tr>
            @endforeach
        </tr>    
        @endforeach
        @else
            <tr>
                <td colspan="11" style="text-align: center;">No data found...</td>
            </tr>
        @endif
    </table>
</body>
</html>