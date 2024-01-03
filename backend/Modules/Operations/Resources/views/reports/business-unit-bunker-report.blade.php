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
                <th colspan="{{ count($vesselBunkers) }}">Bunker Used</th>
                <th colspan="{{ count($vesselBunkers) }}">Bunker Purchased</th>
                <th colspan="{{ count($vesselBunkers) }}">Final Stock</th>
            </tr>
            <tr>
                @for ($i = 0; $i < 3; $i++)
                    @foreach ($vesselBunkers as $bunker)
                        <th><nobr>{{ $bunker }}</nobr></th>
                    @endforeach
                @endfor
            </tr>
        </thead>
        <tbody>
            @if(isset($voyages))
                @foreach($voyages as $voyage)
                <tr>
                    <td><nobr>{{ $voyage->opsVessel->name }}</nobr></td>
                    <td><nobr>{{ $voyage->voyage_count }}</nobr></td>
                    @for ($i = 0; $i < 3; $i++)
                        @foreach ($vesselBunkers as $bunker)
                            <td><nobr></nobr></td>
                        @endforeach
                    @endfor
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</body>
</html>