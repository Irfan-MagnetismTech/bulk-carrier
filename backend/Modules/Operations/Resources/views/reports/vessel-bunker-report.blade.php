<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenditure Report</title>
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

        table tbody tr td {
            text-align: center !important;
        }
    </style>
</head>
<body>
    {{-- <h2 style="text-align: center; font-weight: 600; font-size: 24px; margin: 20px 0;">{{ $port }}</h2> --}}
    <table>
        <thead>
			<tr>
                <th>Date</th>
                <th>Vessel</th>
                <th>Voyage</th>
                <th></th>
                <th>Bunker Used</th>
                <th>Bunker Purchased</th>
                <th>Previous Stock</th>
                <th>Final Stock</th>
            </tr>
        </thead>
        <tbody>

            {{-- <tr>
                <td colspan="{{ 3 + 2 }}"><br></td>
                @foreach($allBunkers as $bunker)
                    <td>
                        {{ ($bunker['previous_stock'] != 0) ? abs($bunker['previous_stock']) : null }}
                    </td>
                @endforeach
                <td></td>

            </tr> --}}



            @if(isset($stockRecords))
                @foreach($stockRecords as $vesselBunkerId => $stockRecord)

                    @foreach($allBunkers as $bunker)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{count($allBunkers)}}"><nobr>{{ \Carbon\Carbon::parse($stockRecord?->date)->format('d/m/Y') }}</nobr></td>
                                <td rowspan="{{count($allBunkers)}}"><nobr>{{ $stockRecord?->opsVessel?->name }}</nobr></td>
                                <td rowspan="{{count($allBunkers)}}"><nobr>{{ $stockRecord?->opsVoyage?->voyage_sequence }}</nobr></td>
                            @endif                        
                            <td> {{ $bunker['name'] }} </td>
                                
                            <td>
                                @php
                                $output = ($stockRecord->type == 'Stock Out') ? $stockRecord->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity') : 0;
                                @endphp
                                {{ ($output !=0 ) ? abs($output) : null }}
                            </td>
                            <td>
                                @php
                                $output = ($stockRecord->type == 'Stock In') ? $stockRecord->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity') : 0;
                                @endphp
                                {{ ($output !=0 ) ? abs($output) : null }}
                            </td>                        
                            <td>
                                {{ ($bunker['previous_stock'] != 0 && $loop->parent->first) ? abs($bunker['previous_stock']) : null }}
                            </td>
                            <td>
                                {{ ($bunker['final_stock'] != 0  && $loop->parent->last) ? abs($bunker['final_stock']) : null }}
                            </td>
                            


                            {{-- @foreach($allBunkers as $bunker)
                            <td>
                                @php
                                $output = ($stockRecord->type == 'Stock In') ? $stockRecord->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity') : 0;
                                @endphp
                                {{ ($output !=0 ) ? abs($output) : null }}
                            </td>
                            @endforeach

                            @foreach($allBunkers as $bunker)
                                <td></td>
                                <td></td>
                            @endforeach --}}
                        </tr>
                    @endforeach

                    {{-- <tr>
                        <td><nobr>{{ $stockRecord?->opsVessel?->name }}</nobr></td>
                        <td><nobr>{{ $stockRecord?->opsVoyage?->voyage_sequence }}</nobr></td>
                        
                        @foreach($allBunkers as $bunker)

                        <td>
                            @php
                            $output = ($stockRecord->type == 'Stock Out') ? $stockRecord->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity') : 0;
                            @endphp
                            {{ ($output !=0 ) ? abs($output) : null }}
                        </td>
                        @endforeach


                        @foreach($allBunkers as $bunker)
                        <td>
                            @php
                            $output = ($stockRecord->type == 'Stock In') ? $stockRecord->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity') : 0;
                            @endphp
                            {{ ($output !=0 ) ? abs($output) : null }}
                        </td>
                        @endforeach

                        @foreach($allBunkers as $bunker)
                            <td></td>
                            <td></td>
                        @endforeach
                    </tr> --}}

                @endforeach
            @endif
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="{{ count($allBunkers) * 2 + 3 }}" style="text-align: center;"><br>
                </td>
                @foreach($allBunkers as $bunker)
                    <td>
                        {{ ($bunker['final_stock'] != 0) ? abs($bunker['final_stock']) : null }}
                    </td>
                @endforeach
            </tr>

        </tfoot> --}}
    </table>

</body>
</html>