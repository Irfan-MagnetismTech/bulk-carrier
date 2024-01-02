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
    $contractVariables = [];
    foreach ($data['opsContractTitle'] as $index => $contract) {
        $contractVariables[strtolower(str_replace(' ', '', $contract['particular']))] = 0;
    }
    foreach ($data['opsExpenditureHeadTitle'] as $index => $expenditure) {
        $expenseVariables[strtolower(str_replace(' ', '', $expenditure['name']))] = 0;
    }



    foreach($data['opsVesselBunkerTitle'] as $title){
        foreach ($data['bunkerMaterialTitle'] as $index => $material) {
            $materialVariables[strtolower(str_replace(' ', '', ($material.$title['particular'])))] = 0;
        }
    }

    $grand_total_cost=0;
@endphp

<body>    
    <table>
        <tr>
            <th colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 8 }}" style="text-align: center; 
            font-size:24px;
            padding:2px;"><h2>Voyage Report</h2></th>
        </tr>
        <tr>
            <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 8 }}" style="text-align: center;">{{ html_entity_decode($data['companyName'], ENT_QUOTES, 'UTF-8') }}</td>
        </tr>
        <tr>
            <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle'])  + 5}}" style="text-align: right;">Date:</td>
            <td colspan="3">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 5}}" style="text-align: right;">Time:</td>
            <td colspan="3">{{ now()->format('g:i A') }}</td>
        </tr>
        @if(isset($data['voyages']))
        <tr>
            <th rowspan="2">DATE</th>
            <th rowspan="2">VOAYAGE NO.</th>
            <th rowspan="2">NAME OF SHIP</th>
            <th rowspan="2">LOADING POINT</th>
            <th rowspan="2">UNLOADING POINT</th>
            <th rowspan="2">CARGO TYPE</th>
            <th rowspan="2">CARGO/TON</th>
            @foreach($data['opsContractTitle'] as $title)
                <th rowspan="2">{{ strtoupper($title['particular']) }}</th>
            @endforeach
            @foreach($data['opsExpenditureHeadTitle'] as $title)
            <th rowspan="2">{{ strtoupper($title['name']) }}</th>
            @endforeach
            <th rowspan="2">Total Cost</th>
            @foreach($data['opsVesselBunkerTitle'] as $title)
            <th>{{ strtoupper($title['particular']) }}</th>
            @endforeach
            <th  rowspan="2">Bunkering</th>
        </tr>
        <tr>
            @foreach($data['opsVesselBunkerTitle'] as $bunker)
                @foreach($data['bunkerMaterialTitle'] as $title)
                <th>{{ strtoupper($title) }}</th>
                @endforeach
            @endforeach

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

                                $field = strtolower(str_replace(' ', '', $title['particular']));                                
                                $total= $sectorData?($sectorData[$month] * $sector['quantity']):0;
                                $contractVariables[$field] += $total;
                            @endphp
                            {{                            
                               $sectorData?($sectorData[$month] * $sector['quantity']):'';
                            }}
                        </td>
                        
                    @endforeach
                    @if($loop->first)
                        @foreach($data['opsExpenditureHeadTitle'] as $index=>$title) 
                            @if (count($voyage->opsVoyageExpenditureEntries->filter(function ($expense) use ($title) {
                                return $expense->opsExpenseHead->name == $title['name'];
                            })))                                    
                                @foreach($voyage->opsVoyageExpenditureEntries->filter(function ($expense) use ($title) {
                                    return $expense->opsExpenseHead->name == $title['name'];
                                }) as $index => $expense)
                                    @php
                                        $total_cost += $expense->amount_bdt;

                                        $grand_total_cost += $total_cost;

                                        $expField = strtolower(str_replace(' ', '', $title['name']));                        
                                        
                                        $expenseVariables[$expField] += $expense->amount_bdt;
                                    @endphp

                                    <td rowspan="{{ count($voyage['opsVoyageSectors']) }}">
                                        {{ $expense->amount_bdt }}
                                    </td>
                                @endforeach
                            @else
                                <td></td>
                            @endif
                        @endforeach
                        <td rowspan="{{ count($voyage['opsVoyageSectors']) }}">{{ $total_cost }}</td>

                        @foreach($data['opsVesselBunkerTitle'] as $title)
                            @foreach($voyage['opsVesselBunkers'] as $vesselBunker)
                                @if ($vesselBunker->type == 'Stock Out')                                    
                                    @if (count($vesselBunker->opsBunkers->where('particular',$title['particular'])))                                    
                                        @foreach($vesselBunker->opsBunkers as $bunker)
                                            @foreach($data['bunkerMaterialTitle'] as $material)
                                                @if ($material == $bunker->scmMaterial->name)     
                                                    @php
                                                        $matField = strtolower(str_replace(' ', '', ($material.$title['particular'])));
                                                        $total= $sectorData?($sectorData[$month] * $sector['quantity']):0;
                                                        $materialVariables[$matField] += $bunker->quantity;
                                                    @endphp                                      
                                                    {{-- @dd($materialVariables) --}}
                                                    <td rowspan="{{count($voyage['opsVoyageSectors'])}}">{{$bunker->quantity}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @else
                                        <td rowspan="{{count($voyage['opsVoyageSectors'])}}" colspan="{{count($data['bunkerMaterialTitle'])}}"></td>
                                    @endif                          
                                @endif
                            @endforeach
                        @endforeach
                        @foreach($voyage['opsVesselBunkers'] as $vesselBunker)
                            @if ($vesselBunker->type =='Stock In')                                
                                @if (count($vesselBunker->opsBunkers))                                   
                                    @foreach($vesselBunker->opsBunkers as $bunker)
                                        @foreach($data['bunkerMaterialTitle'] as $material)
                                            @if ($material == $bunker->scmMaterial->name)
                                                <td rowspan="{{count($voyage['opsVoyageSectors'])}}">{{  $bunker->quantity }}</td>
                                            @else
                                                <td rowspan="{{count($voyage['opsVoyageSectors'])}}"></td>
                                            @endif
                                        @endforeach
                                    
                                    @endforeach
                                @endif    
                            @else
                                <td rowspan="{{count($voyage['opsVoyageSectors'])}}"></td>                        
                            @endif
                        @endforeach
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
            <th>{{$total_cargo}}</th>
            @foreach($data['opsContractTitle'] as $title)                        	
                <th>
                    @php
                        $field = strtolower(str_replace(' ', '', $title['particular']));
                    @endphp
                    {{
                        $contractVariables[$field];
                    }}
                </th>
            @endforeach
            @foreach($data['opsExpenditureHeadTitle'] as $title)                        	
                <th>
                    @php
                        $field = strtolower(str_replace(' ', '', $title['name']));
                    @endphp
                    {{
                        $expenseVariables[$field];
                    }}
                </th>
            @endforeach
            <th>{{$grand_total_cost}}</th>
            @foreach($data['opsVesselBunkerTitle'] as $bunker)                        	
                @foreach($data['bunkerMaterialTitle'] as $matrial)                        	
                    <th>
                        @php
                            $materialfield = strtolower(str_replace(' ', '', ($matrial.$bunker['particular'])));
                        @endphp
                        {{
                            $materialVariables[$materialfield];
                        }}
                    </th>
                @endforeach
            @endforeach
        </tr>
        @else
            <tr>
                <td colspan="11" style="text-align: center;">No data found...</td>
            </tr>
        @endif
    </table>
</body>
</html>