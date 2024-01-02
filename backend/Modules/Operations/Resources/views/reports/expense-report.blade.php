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
                <th rowspan="2">Voyage</th>
                <th rowspan="2">Sailing Date</th>
                <th rowspan="2">Transit Date</th>
                @foreach ($heads as $head)
                    <th colspan="{{ (!empty($head['opsSubHeads'])) ? count($head['opsSubHeads']) : 0 }}">
                        <center align="center">{{ $head['name'] }}</center>
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($heads as $head)
                    @if(count($head['opsSubHeads'])>0)
                        @foreach ($head['opsSubHeads'] as $sub_head)

                            <th>
                                {{ $sub_head->name }}
                            </th>

                        @endforeach
                    @else
                        <th></th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if(isset($entryGroups))
                @foreach($entryGroups as $expense_head_id => $entries)
                <tr>
                    <td><nobr>{{ $entries->first()->vessel }}</nobr></td>
                    <td><nobr>{{ $entries->first()->voyage }}</nobr></td>
                    <td><nobr>{{ ($entries->first()->sail_date) ? \Carbon\Carbon::parse($entries->first()->sail_date)->format('d/m/Y \a\t h:i A') : '' }}</nobr></td>
                    <td><nobr>{{ ($entries->first()->transit_date) ? \Carbon\Carbon::parse($entries->first()->transit_date)->format('d/m/Y \a\t h:i A') : ''}}</nobr></td>

                    @foreach ($heads as $head)
                        @if(count($head['opsSubHeads']) <= 0)
                            <td style="text-align: right !important;">
                                {{ (collect($entries)->where('ops_expense_head_id', $head['id'])->sum('amount_bdt') > 0) ? number_format(collect($entries)->where('ops_expense_head_id', $head['id'])->sum('amount_bdt'), 2) : null }}
                            </td>
                        @else
                            @foreach ($head['opsSubHeads'] as $sub_head)
                                <td style="text-align: right !important;">
                                    {{ (collect($entries)->where('ops_expense_head_id', $sub_head->id)->sum('amount_bdt') > 0) ? number_format(collect($entries)->where('ops_expense_head_id', $sub_head->id)->sum('amount_bdt'), 2) : null }}
                                </td>
                            @endforeach
                        @endif
                    @endforeach


                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</body>
</html>