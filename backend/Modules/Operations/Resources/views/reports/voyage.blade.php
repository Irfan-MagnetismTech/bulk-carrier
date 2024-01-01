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
@php
    $total_cargo= 0;
    $total_feight_rate= 0;
    $total_unloading= 0;
    $total_dissel= 0;
@endphp
<body>    
    <table>
        <tr>
            <th colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + 8 }}" style="text-align: center; 
            font-size:24px;
            padding:2px;"><h2>Voyage Report</h2></th>
        </tr>
        <tr>
            <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + 8 }}" style="text-align: center;">{{ html_entity_decode($data['companyName'], ENT_QUOTES, 'UTF-8') }}</td>
        </tr>
        <tr>
            <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle'])  + 5}}" style="text-align: right;">Date:</td>
            <td colspan="3">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + 5}}" style="text-align: right;">Time:</td>
            <td colspan="3">{{ now()->format('g:i A') }}</td>
        </tr>
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
            <th>Total Cost</th>
        </tr>
        @foreach ($data['voyages'] as $key => $voyage)   
            {{-- @dd(count($voyage['opsVoyageSectors'])>1) --}}
            @php
                $total_cargo+=$voyage['opsVoyageSectors']->sum('quantity');
            @endphp
            @foreach($voyage['opsVoyageSectors'] as $key1=>$sector)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{count($voyage['opsVoyageSectors'])}}">{{$voyage['sail_date']}}</td>
                        <td rowspan="{{count($voyage['opsVoyageSectors'])}}">{{$voyage['voyage_no']}}</td>
                        <td rowspan="{{count($voyage['opsVoyageSectors'])}}">{{$voyage['opsVessel']['name']}}</td>
                    @endif
                    <td>{{ $sector['loading_point'] }}</td>
                    <td>{{ $sector['unloading_point'] }}</td>

                    {{-- @if($loop->first)
                    @endif --}}
                    <td rowspan="">{{$voyage['opsCargoType']['cargo_type']}}</td>
                    <td rowspan="">{{$sector['quantity']?$sector['quantity']:'00'}}</td>
                    @php
                        $total_cost=0;
                    @endphp
                    @foreach($data['opsContractTitle'] as $title)                        	
                        <td>
                            @php
                                $sectorData=$sector->opsContractTariff?->opsCargoTariff?->opsCargoTariffLines->where('particular', $title['particular'])->first();
                                $month= $sector->opsContractTariff['tariff_month'];
                                
                            @endphp
                            {{
                            
                               $sectorData?($sectorData[$month] * $sector['quantity']):'';
                            }}
                        </td>
                        
                    @endforeach
                    @if($loop->first)
                        @foreach($data['opsExpenditureHeadTitle'] as $index=>$title) 
                                @foreach($voyage->opsVoyageExpenditureEntries as $index=>$expense)                    
                                    @if ($expense->opsExpenseHead->name == $title['name'])
                                        @php
                                            $total_cost += $expense->amount_bdt;
                                        @endphp
                                        <td rowspan="{{ count($voyage['opsVoyageSectors']) }}">
                                            {{ $expense->amount_bdt }}
                                        </td>
                                    @endif                                    
                                @endforeach
                                @if (count($data['opsExpenditureHeadTitle']) > count($voyage['opsVoyageExpenditureEntries']) && $loop->first)
                                     @for ($i=0; $i < (count($data['opsExpenditureHeadTitle']) - count($voyage['opsVoyageExpenditureEntries'])); $i++)
                                     <td></td>
                                    @endfor
                                @endif
                        @endforeach
                        <td rowspan="{{ count($voyage['opsVoyageSectors']) }}">{{ $total_cost }}</td>
                    @endif
                </tr>
            @endforeach
        </tr>    
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$total_cargo}}</td>
            <td></td>
        </tr>
        @else
            <tr>
                <td colspan="11" style="text-align: center;">No data found...</td>
            </tr>
        @endif
    </table>
</body>
</html>