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
                <th rowspan="2">Vessel</th>
                <th rowspan="2">Voyage</th>
                <th colspan="{{ count($allBunkers) }}">Bunker Used</th>
                <th colspan="{{ count($allBunkers) }}">Bunker Purchased</th>
                <th colspan="{{ count($allBunkers) }}">Final Stock</th>
            </tr>
            <tr>
                @for ($i = 1; $i < 4; $i++)
                    @foreach($allBunkers as $bunker)
                    <th> {{ $bunker['name'] }} </th>
                    @endforeach
                @endfor
            </tr>
        </thead>
        <tbody>
            @if(isset($voyages))
                @foreach($voyages as $voyage)





                <tr>
                    <td><nobr>{{ $voyage->opsVessel->name }}</nobr></td>
                    <td><nobr>{{ $voyage->voyage_sequence }}</nobr></td>
                    
                    @foreach($allBunkers as $bunker)
                    <td>
                        @php
                        $output = 0;
                        collect($voyage->bunkers['Stock Out'])->map(function($stock) use(&$output, $bunker) {
                            $output += $stock->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity');
                        })
                        @endphp
                        {{ abs($output) }}
                    </td>
                    @endforeach

                    @foreach($allBunkers as $bunker)
                    <td>
                        @php
                        $output = 0;
                        if(isset($voyage->bunkers['Stock In'])) {
                            collect($voyage->bunkers['Stock In'])->map(function($stock) use(&$output, $bunker) {
                                $output += $stock->stockable->where('scm_material_id', $bunker['scm_material_id'])->sum('quantity');
                            });
                        }
                        
                        @endphp
                        {{ ($output != 0) ? abs($output) : null }}
                    </td>
                    @endforeach

                    @foreach($allBunkers as $bunker)
                    <td>
                        
                    </td>
                    @endforeach
                </tr>




                @endforeach
            @endif
        </tbody>
    </table>

</body>
</html>