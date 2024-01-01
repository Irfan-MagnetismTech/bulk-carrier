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
    @foreach ($portWiseHeads as $port => $heads)
    <h2 style="text-align: center; font-weight: 600; font-size: 24px; margin: 20px 0;">{{ $port }}</h2>
    <table>
        <thead>
			<tr>
                <th>Vessel</th>
                <th>Voyage</th>
                <th>Arrival Date</th>
                <th>Sailing Date</th>
                @foreach ($heads as $head)
                    <th colspan="{{ (!empty($head['head']['subheads'])) ? (count($realSubheads[$port][$head['head']['id']]) - 1) : 0 }}">
                        <center align="center">{{ $head['head']['name'] }}</center>
                    </th>
                @endforeach
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @foreach ($heads as $head)
                    @if(count($head['head']['subheads'])>0)
                        @foreach ($head['head']['subheads'] as $sub_head)

                            @if(in_array($sub_head->id, $portWiseHeadIds[$port]->toArray()))
                            <th>
                                {{ $sub_head->name }}
                            </th>
                            @endif

                        @endforeach
                    @else
                        <th></th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- portWiseHeadIds -->
            @if(isset($entryMultiGroup[$port]))
            @foreach($entryMultiGroup[$port] as $voyPairId => $entries)
            <tr>
                <td>{{ $entries->first()->vessel }}</td>
                <td>{{ $entries->first()->voyage }}</td>
                <td>{{ \Carbon\Carbon::parse($entries->first()->arrival)->format('d/m/Y \a\t Hi') }}</td>
                <td>{{ \Carbon\Carbon::parse($entries->first()->sailing)->format('d/m/Y \a\t Hi') }}</td>
                @foreach ($heads as $head)
                    @if(count($head['head']['subheads']) <= 0)
                        <td>
                            <!-- Real Head -->
                            <!-- {{ $head['head']['id'] }} -->
                            {{ (collect($entries)->where('particular_id', $head['head']['id'])->sum('amount_bdt') > 0) ? number_format(collect($entries)->where('particular_id', $head['head']['id'])->sum('amount_bdt'), 2) : null }}
                        </td>
                    @else
                        @foreach ($head['head']['subheads'] as $sub_head)
                            @if(in_array($sub_head->id, $portWiseHeadIds[$port]->toArray()))
                            <td>
                                <!-- Sub Head -->
                                <!-- {{ $sub_head->id }}  -->
                                {{ (collect($entries)->where('particular_id', $sub_head->id)->sum('amount_bdt') > 0) ? number_format(collect($entries)->where('particular_id', $sub_head->id)->sum('amount_bdt'), 2) : null }}
                            </td>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    @endforeach


    <h2 style="text-align: center; font-weight: 600; font-size: 24px; margin: 20px 0;">Other Expenses</h2>
    <table>
        <thead>
			<tr>
                <th>Vessel</th>
                <th>Voyage</th>
                <th>Arrival Date</th>
                <th>Sailing Date</th>
                @foreach ($globalHeadEntries as $head)

                    <th colspan="{{ (!empty($head['subheads'])) ? (count($head['subheads'])) : 0 }}">
                        <center align="center">{{ $head['name'] }}</center>
                    </th>
                @endforeach
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @foreach ($globalHeadEntries as $head)
                    @if(count($head['subheads'])>0)
                        @foreach ($head['subheads'] as $sub_head)
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
            <!-- portWiseHeadIds -->
            @foreach($entryGroupWithoutPort as $entries)
            <tr>
                <td>{{ $entries->first()->vessel }}</td>
                <td>{{ $entries->first()->voyage }}</td>
                <td>{{ \Carbon\Carbon::parse($entries->first()->arrival)->format('d/m/Y \a\t Hi') }}</td>
                <td>{{ \Carbon\Carbon::parse($entries->first()->sailing)->format('d/m/Y \a\t Hi') }}</td>
                @foreach ($globalHeadEntries as $head)
                    @if(count($head['subheads']) <= 0)
                        <td>
                            <!-- Real Head -->
                            <!-- {{ $head['id'] }} -->
                            {{ (collect($entries)->where('particular_id', $head['id'])->sum('amount_bdt') > 0) ? number_format(collect($entries)->where('particular_id', $head['id'])->sum('amount_bdt'), 2) : null }}
                        </td>
                    @else
                        @foreach ($head['subheads'] as $sub_head)
                            <td>
                                <!-- Sub Head -->
                                <!-- {{ $sub_head->id }}  -->
                                {{ (collect($entries)->where('particular_id', $sub_head->id)->sum('amount_bdt') > 0) ? number_format(collect($entries)->where('particular_id', $sub_head->id)->sum('amount_bdt'), 2) : null }}
                            </td>
                        @endforeach
                    @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>