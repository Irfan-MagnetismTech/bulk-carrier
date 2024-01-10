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
    </style>
</head>
<body>
    {{-- <h2 style="text-align: center; font-weight: 600; font-size: 24px; margin: 20px 0;">{{ $port }}</h2> --}}
    <table>
        <thead>
			<tr>
                <th rowspan="2">Vessel</th>
                <th rowspan="2">Voyages</th>
                <th>Bunker</th>
                <th>Previous Stock</th>
                <th>Bunker Purchased</th>
                <th>Bunker Used</th>
                <th>Final Stock</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($voyages))
                @foreach($voyages as $voyage)
                    @foreach ($allBunkers as $bunker)                        
                    <tr>
                            @if($loop->first)
                                <td rowspan="{{count($allBunkers)}}"><nobr>{{ $voyage['vessel_name'] }}</nobr></td>
                                <td rowspan="{{count($allBunkers)}}"><nobr>{{ $voyage['voyage_count'] }}</nobr></td>
                            @endif
                            <th><nobr>{{ $bunker['name'] }}</nobr></th>
                            <td><nobr>{{ ($voyage['previous_stock'][$bunker['scm_material_id']] != 0) ? $voyage['previous_stock'][$bunker['scm_material_id']] : null }}</nobr></td>
                            <td><nobr>{{ ($voyage['stock_in'][$bunker['scm_material_id']] != 0) ? $voyage['stock_in'][$bunker['scm_material_id']] : null }}</nobr></td>
                            <td><nobr>{{ ($voyage['stock_out'][$bunker['scm_material_id']] != 0 ) ? abs($voyage['stock_out'][$bunker['scm_material_id']]) : null }}</nobr></td>
                            <td><nobr>{{ ($voyage['current_stock'][$bunker['scm_material_id']] != 0) ? $voyage['current_stock'][$bunker['scm_material_id']] : null }}</nobr></td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7"></td>
            </tr> 

            @foreach ($allBunkers as $bunker)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{count($allBunkers)}}">
                            Summary
                        </td>
                        <td rowspan="{{count($allBunkers)}}">
                            {{ $voyages->sum('voyage_count') }}
                        </td>
                    @endif
                    @php
                        $material_id = $bunker['scm_material_id'];
                    @endphp
                        <th><nobr>{{ $bunker['name'] }}</nobr></th>
                        <td><nobr>{{ ($voyages->pluck("previous_stock.$material_id")->sum() != 0) ? $voyages->pluck("previous_stock.$material_id")->sum() : null }}</nobr></td>
                        <td><nobr>{{ ($voyages->pluck("stock_in.$material_id")->sum() != 0 ) ? $voyages->pluck("stock_in.$material_id")->sum() : null }}</nobr></td>
                        <td><nobr>{{ ($voyages->pluck("stock_out.$material_id")->sum() != 0 ) ? abs($voyages->pluck("stock_out.$material_id")->sum()) : null }}</nobr></td>
                        <td><nobr>{{ ($voyages->pluck("current_stock.$material_id")->sum() != 0 ) ? $voyages->pluck("current_stock.$material_id")->sum() : null }}</nobr></td>
                </tr>
            @endforeach
                 
        </tfoot>
    </table>

</body>
</html>