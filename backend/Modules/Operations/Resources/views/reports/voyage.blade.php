<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voyage Report</title>
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
        }
    </style>
</head>
@php
    $total_cargo= 0;
    $contractVariables = [];

    // dd($data['opsContractTitle'])
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

    foreach ($data['bunkerMaterialTitle'] as $index => $material) {
        $materialVariables[strtolower(str_replace(' ', '', ($material.'stock_in')))] = 0;
    }


    $voyageIds=[];

    $grand_total_cost=0;
    $sub_total_bunkering=0;
@endphp

<body>    
    <table>
        <thead>
            <tr>
                <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 12 }}" style="text-align: center; 
                font-size:24px;
                padding:2px;"><h2>Voyage Report</h2></td>
            </tr>
            <tr>
                <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 12 }}" style="text-align: center;">{{ html_entity_decode($data['companyName'], ENT_QUOTES, 'UTF-8') }}</td>
            </tr>
            <tr>
                <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle'])  + 8}}" style="text-align: right;">Date:</td>
                <td colspan="4">{{ now()->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 8}}" style="text-align: right;">Time:</td>
                <td colspan="4">{{ now()->format('g:i A') }}</td>
            </tr>
            @if(isset($data['vesselBunkers']))
                <tr>
                    <th rowspan="3">DATE</th>
                    <th rowspan="3">BUNKER USAGE TYPE</th>
                    <th rowspan="3">VOAYAGE NO.</th>
                    <th rowspan="3">NAME OF SHIP</th>
                    <th rowspan="3">LOADING POINT</th>
                    <th rowspan="3">UNLOADING POINT</th>
                    <th rowspan="3">CARGO TYPE</th>
                    <th rowspan="3">CARGO/TON</th>
                    @foreach($data['opsContractTitle'] as $title)
                        <th rowspan="3">{{ strtoupper($title['particular']) }}</th>
                    @endforeach
                    <th colspan="{{count($data['opsExpenditureHeadTitle']) + 1}}" style="height: 15px;">COST</th>
                    <th colspan="{{count($data['opsVesselBunkerTitle'])}}" style="height: 15px;">Stock Out</th>
                    <th colspan="{{count($data['bunkerMaterialTitle'])}}">Stock In</th>
                </tr>
                <tr>
                    @foreach($data['opsExpenditureHeadTitle'] as $title)
                    <th rowspan="2">{{ strtoupper($title['name']) }}</th>
                    @endforeach
                    <th rowspan="2">Total Cost</th>
                    @foreach($data['opsVesselBunkerTitle'] as $title)
                    <th>{{ strtoupper($title['particular']) }}</th>
                    @endforeach
                    
                    <th colspan="{{count($data['bunkerMaterialTitle'])}}">Bunkering</th>
                </tr>
                <tr>
                    @foreach($data['opsVesselBunkerTitle'] as $bunker)
                        @foreach($data['bunkerMaterialTitle'] as $title)
                            <th>{{ strtoupper($title) }}</th>
                        @endforeach
                    @endforeach
                    @foreach($data['bunkerMaterialTitle'] as $title)
                        <th>{{ strtoupper($title) }}</th>
                    @endforeach
        
                </tr>
            @endif
        </thead>
        <tbody>
            @if(isset($data['vesselBunkers']))
    
                @php
                    $voyage_id=null;
                @endphp
                @foreach ($data['vesselBunkers'] as $key => $vesselBunker)

                    @if(isset($vesselBunker?->opsVoyage))
                        
                        @php                            
                            $uniqueVoyageIds = array_unique($voyageIds);
                            $vesselBunkerOpsVoyageId = (array) $vesselBunker->ops_voyage_id;
                            $intersection = array_intersect($uniqueVoyageIds, $vesselBunkerOpsVoyageId);
                            if (empty($intersection)) {
                                $total_cargo+=$vesselBunker?->opsVoyage?->opsVoyageSectors->sum('quantity');
                            }
                        @endphp

                        @foreach($vesselBunker?->opsVoyage?->opsVoyageSectors as $key1=>$sector)
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{$vesselBunker['date']}}</td>
                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{ $vesselBunker->type }}</td>
                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{ $vesselBunker->opsVoyage->voyage_no }}</td>
                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{$vesselBunker->opsVessel->name}}</td>
                                @endif

                                <td>{{ ($vesselBunker->type=='Stock Out')?$sector['loading_point']:'' }}</td>
                                <td>{{ ($vesselBunker->type=='Stock Out')?$sector['unloading_point']:'' }}</td>
            
                                <td rowspan="">{{($vesselBunker->type=='Stock Out')?$vesselBunker?->opsVoyage?->opsCargoType?->cargo_type : ''}}</td>
                                <td rowspan="">{{ ($vesselBunker->type=='Stock Out')?($sector['quantity']?$sector['quantity']:''): ''}}</td>
                                @php
                                    $total_cost=0;
                                @endphp
                                @foreach($data['opsContractTitle'] as $title)                        	
                                    <td>
                                        @php
                                            $sectorData=$sector->opsContractTariff?->opsCargoTariff?->opsCargoTariffLines->where('particular', $title['particular'])->first();
                                            $month= $sector->opsContractTariff['tariff_month'];
                                           
                                            $uniqueVoyageIds = array_unique($voyageIds);
                                            $vesselBunkerOpsVoyageId = (array) $vesselBunker->ops_voyage_id;

                                            $intersection = array_intersect($uniqueVoyageIds, $vesselBunkerOpsVoyageId);

                                            if (empty($intersection)) {
                                                $field = strtolower(str_replace(' ', '', $title['particular']));                                
                                                $total = $sectorData ? ($sectorData[$month] * $sector['quantity']) : 0;
                                                $contractVariables[$field] += $total;
                                            }
                                        @endphp
                                        {{ ($vesselBunker->type=='Stock Out')?($sectorData?($sectorData[$month] * $sector['quantity']):''):'' }}
                                    </td>
                                    
                                @endforeach
                                @if($loop->first)
                                    @foreach($data['opsExpenditureHeadTitle'] as $index=>$title) 
                                        @if (count($vesselBunker?->opsVoyage->opsVoyageExpenditureEntries->filter(function ($expense) use ($title) {
                                            return $expense->opsExpenseHead->name == $title['name'];
                                        })))                                    
                                            @foreach($vesselBunker?->opsVoyage->opsVoyageExpenditureEntries->filter(function ($expense) use ($title) {
                                                return $expense->opsExpenseHead->name == $title['name'];
                                            }) as $index => $expense)
                                                @php
            
                                                    $expField = strtolower(str_replace(' ', '', $title['name']));                        
                                                   
                                                    $uniqueVoyageIds = array_unique($voyageIds);
                                                    $vesselBunkerOpsVoyageId = (array) $vesselBunker->ops_voyage_id;

                                                    $intersection = array_intersect($uniqueVoyageIds, $vesselBunkerOpsVoyageId);

                                                    if (empty($intersection)) {
                                                        if($vesselBunker->type=='Stock Out'){
                                                            $total_cost += $expense->amount_bdt;
                                                            $grand_total_cost += $total_cost;
                                                            $expenseVariables[$expField] += $expense->amount_bdt;
                                                        }
                                                    }
                                                   

                                                @endphp
            
                                                <td rowspan="{{ count($vesselBunker->opsVoyage->opsVoyageSectors) }}">
                                                    {{ ($vesselBunker->type=='Stock Out')?$expense->amount_bdt : '' }}
                                                </td>
                                            @endforeach
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                    <td rowspan="{{ count($vesselBunker->opsVoyage->opsVoyageSectors) }}">{{ $total_cost? $total_cost : '' }}</td>
                                    @foreach($data['opsVesselBunkerTitle'] as $title)
                                        @foreach($vesselBunker?->opsVoyage?->opsVesselBunkers->where('type','Stock Out') as $key=>$vessel_bunker)                                    
                                            @if ($loop->first)                                    
                                                @if (count($vessel_bunker?->opsBunkers->where('particular',$title['particular'])))                                    
                                                    @foreach($vessel_bunker?->opsBunkers as $bunker)
                                                        @foreach($data['bunkerMaterialTitle'] as $material)
                                                            @if ($material == $bunker->scmMaterial->name)     
                                                                @php
                                                                    if($vesselBunker->type=='Stock Out'){
                                                                        $matField = strtolower(str_replace(' ', '', ($material.$title['particular'])));
                                                                        $total= $sectorData?($sectorData[$month] * $sector['quantity']):0;
                                                                        $materialVariables[$matField] += $bunker->quantity;
                                                                    }
                                                                    
                                                                @endphp
                                                                <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{  ($vesselBunker->type=='Stock Out')? $bunker->quantity : ''}}</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else                                        
                                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}"></td>                                    
                                                @endif                          
                                            @endif
                                        @endforeach

                                        {{-- @if ($vesselBunker->type =='Stock Out')
                                                @if (count($vesselBunker->stockable)) 
                                                    @foreach($vesselBunker?->stockable as $bunker)
                                                        @if($loop->first)               
                                                            @foreach($data['bunkerMaterialTitle'] as $material)
                                                                @if ($material == $bunker->scmMaterial->name)     
                                                                        @php
                                                                            if($vesselBunker->type=='Stock Out'){
                                                                                $matField = strtolower(str_replace(' ', '', ($material.$title['particular'])));
                                                                                $total= $sectorData?($sectorData[$month] * $sector['quantity']):0;
                                                                                $materialVariables[$matField] += $bunker->quantity;
                                                                            }
                                                                        @endphp
                                                                        <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{  ($vesselBunker->type=='Stock Out')? $bunker->quantity : ''}}</td>
                                                                    @else
                                                                        <td></td>
                                                                @endif
                                                            @endforeach                                               
                                                        @endif
                                                    @endforeach                                               
                                                @endif   
                                            @else
                                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}"></td>
                                            @endif --}}
                                        
                                    @endforeach
            
                                    {{-- @foreach($vesselBunker->opsVoyage->opsVesselBunkers as $vessel_bunker) --}}
                                        @if ($loop->first)
                                            @if ($vesselBunker->type =='Stock In')
                                                @if (count($vesselBunker->stockable))                   
                                                    @if (count($vesselBunker->stockable->whereNotNull('quantity')))
                                                        @foreach($vesselBunker->stockable->whereNotNull('quantity') as $bunker)
                                                            @foreach($data['bunkerMaterialTitle'] as $material)
                                                                @if ($material == $bunker->scmMaterial->name)     
                                                                    @php
                                                                            $matFieldin = strtolower(str_replace(' ', '', ($material.'stock_in')));
                                                                            $total= $sectorData?($sectorData[$month] * $sector['quantity']):0;
                                                                            $materialVariables[$matFieldin] += $bunker->quantity;
                                                                        
                                                                    @endphp
                                                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}">{{ ($bunker->quantity)? $bunker->quantity: '' }}</td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @endif                                               
                                                @endif   
                                            @else
                                                    <td rowspan="{{count($vesselBunker->opsVoyage->opsVoyageSectors)}}"></td>
                                            @endif
                                        @endif
                                    {{-- @endforeach --}}
            
                                    
                                @endif
                                    
                                    
                            </tr>
                        @endforeach  
                        
                        @php
                            $voyageIds[]=$vesselBunker->ops_voyage_id;                            
                        @endphp
                    @elseif(isset($vesselBunker))                        
                            {{-- @dd($vesselBunker) --}}
                        <tr>
                            <td>{{$vesselBunker['date']}}</td>
                            <td>{{ $vesselBunker->type }}</td>
                            <td></td>
                            <td>{{$vesselBunker->opsVessel->name}}</td>
                            <td></td>
                            <td></td>

                            <td rowspan=""></td>
                            <td rowspan=""></td>
                            @foreach($data['opsContractTitle'] as $title)                        	
                                <td></td>                                
                            @endforeach

                                @foreach($data['opsExpenditureHeadTitle'] as $index=>$title)
                                        <td></td>
                                @endforeach
                                <td rowspan=""></td>
                                @foreach($data['opsVesselBunkerTitle'] as $title)
                                    <td></td>
                                @endforeach

                                @if ($vesselBunker->type =='Stock In')
                                    @if (count($vesselBunker->stockable))                   
                                        @if (count($vesselBunker->stockable->whereNotNull('quantity')))
                                            @foreach($vesselBunker->stockable->whereNotNull('quantity') as $bunker)
                                                @foreach($data['bunkerMaterialTitle'] as $material)
                                                    @if ($material == $bunker->scmMaterial->name)     
                                                        @php
                                                            $matFieldin = strtolower(str_replace(' ', '', ($material.'stock_in')));
                                                            $materialVariables[$matFieldin] += abs($bunker->quantity);
                                                        @endphp
                                                        <td rowspan="">{{abs($bunker->quantity)}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif                                               
                                    @endif   
                                @else
                                        <td rowspan=""></td>
                                @endif

                                
                                
                                
                                
                        </tr>
                    @endif




                    @php
                        $voyage_id=$sector->ops_voyage_id;
                    @endphp
                @endforeach
                <tr>
                    <td></td>
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
        
                    @foreach($data['bunkerMaterialTitle'] as $matrial)                        	
                        <th>
                            @php
                                $materialfieldIn = strtolower(str_replace(' ', '', ($matrial.'stock_in')));
                            @endphp
                            {{
                                $materialVariables[$materialfieldIn];
                            }}
                        </th>
                    @endforeach
                </tr>
            @else
                <tr>
                    <td colspan="{{ count($data['opsContractTitle']) + count($data['opsExpenditureHeadTitle']) + count($data['opsVesselBunkerTitle']) + 12 }}" style="text-align: center;">No data found...</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>