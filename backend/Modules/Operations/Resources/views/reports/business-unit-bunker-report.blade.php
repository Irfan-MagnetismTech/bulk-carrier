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
                <th colspan="{{ count($allBunkers) }}">Previous Stock</th>
                <th colspan="{{ count($allBunkers) }}">Bunker Purchased</th>
                <th colspan="{{ count($allBunkers) }}">Bunker Used</th>
                <th colspan="{{ count($allBunkers) }}">Final Stock</th>
            </tr>
            <tr>
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($allBunkers as $bunker)
                        <th><nobr>{{ $bunker['name'] }}</nobr></th>
                    @endforeach
                @endfor
            </tr>
        </thead>
        <tbody>
            @if(isset($voyages))
                @foreach($voyages as $voyage)
                <tr>
                    <td><nobr>{{ $voyage['vessel_name'] }}</nobr></td>
                    <td><nobr>{{ $voyage['voyage_count'] }}</nobr></td>

                    @foreach ($allBunkers as $bunker)
                        <td><nobr>{{ $voyage['previous_stock'][$bunker['scm_material_id']] }}</nobr></td>
                    @endforeach

                    @foreach ($allBunkers as $bunker)
                        <td><nobr>{{ $voyage['stock_in'][$bunker['scm_material_id']] }}</nobr></td>
                    @endforeach

                    @foreach ($allBunkers as $bunker)
                        <td><nobr>{{ $voyage['stock_out'][$bunker['scm_material_id']]*-1 }}</nobr></td>
                    @endforeach

                    @foreach ($allBunkers as $bunker)
                        <td><nobr>{{ $voyage['current_stock'][$bunker['scm_material_id']] }}</nobr></td>
                    @endforeach
                </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td>
                    Summary
                </td>
                <td>
                    {{ $voyages->sum('voyage_count') }}
                </td>
                    @foreach ($allBunkers as $bunker)
                        @php
                            $material_id = $bunker['scm_material_id'];
                        @endphp
                        <td><nobr>{{ $voyages->pluck("previous_stock.$material_id")->sum() }}</nobr></td>
                    @endforeach

                    @foreach ($allBunkers as $bunker)
                        @php
                            $material_id = $bunker['scm_material_id'];
                        @endphp
                        <td><nobr>{{ $voyages->pluck("stock_in.$material_id")->sum() }}</nobr></td>
                    @endforeach

                    @foreach ($allBunkers as $bunker)
                        @php
                            $material_id = $bunker['scm_material_id'];
                        @endphp
                        <td><nobr>{{ $voyages->pluck("stock_out.$material_id")->sum() * -1 }}</nobr></td>
                    @endforeach

                    @foreach ($allBunkers as $bunker)
                        @php
                            $material_id = $bunker['scm_material_id'];
                        @endphp
                        <td><nobr>{{ $voyages->pluck("current_stock.$material_id")->sum() }}</nobr></td>
                    @endforeach
            </tr>
        </tfoot>
    </table>

</body>
</html>