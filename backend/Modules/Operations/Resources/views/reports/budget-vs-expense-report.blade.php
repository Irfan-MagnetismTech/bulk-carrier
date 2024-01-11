<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget and Expense Comparison</title>
    <style>
        table, tr, td, th {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
        table, thead, tbody {
            width: 100%;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin: 15px auto; font-weight: 600; font-size: 1.7rem">Budget vs Expense Report</h2>
    <table>
        <thead>
            <tr>
                <td colspan="3"></td>

                @foreach($voyages as $voyage)
                <td colspan="2" style="text-align: center;">
                    {{ $voyage->combined_name }} <br />
                    {{ $voyage->firstVoyage->voyage }} +
                    {{ $voyage->secondVoyage->voyage }}
                </td>
                @endforeach
            </tr>
            <tr>
                <th>Head</th>
                <th>Port</th>
                <th>Curr</th>
                @foreach($voyages as $voyage)
                    <th>Budget</th>
                    <th>Actual</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($portWiseHeads as $port => $heads)
                @foreach($heads as $head)
                <tr>
                    @foreach($voyages as $voyage)

                    @if($loop->first)
                    <td>{{ $head['name'] }}</td>
                    <td>{{ $port }}</td>
                    <td>USD</td>
                    @endif

                        <td>
                            @php
                                if(!empty($voyage->budgetInfo)) {
                                    $budget = $voyage->budgetInfo->where('particular_id', $head['head_id'])->where('port', $port)->values()->first()?->amount;
                                } else {
                                    $budget = 0;
                                }
                            @endphp
                            {{ ($budget > 0) ? number_format($budget, 2) : null }}
                        </td>
                        <td>
                            @php
                            $expenses = $mappingBudgetExpenses->where('id', $voyage->id)->first()->expenses[$port]->where('particular_id', $head['head_id'])->values();
                            if(!empty($expenses)) {
                                echo ($expenses->sum('amount') > 0) ? number_format($expenses->sum('amount'), 2) : null;
                            }
                            @endphp

                        </td>
                    @endforeach

                </tr>
                @endforeach
            @endforeach

            @foreach($globalHeads as $head)
                @if(!empty($head['subheads']))
                @foreach($head['subheads'] as $sub_head)
                    <tr>
                    @foreach($voyages as $voyage)

                        @if($loop->first)
                        <td>{{ $sub_head['name'] }} - {{ $head['name'] }}</td>
                        <td></td>
                        <td>USD</td>
                        @endif

                            <td>
                                @php
                                if(!empty($voyage->budgetInfo)) {
                                    $budget = $voyage?->budgetInfo?->where('particular_id', $sub_head['id'])->where('port', null)->values()->first()?->amount;
                                } else {
                                    $budget = 0;
                                }
                                @endphp
                                {{ ($budget > 0) ? number_format($budget, 2) : null }}
                            </td>
                            <td>
                                @php
                                    
                                    $expenses = $mappingBudgetExpenses->where('id', $voyage->id)->first()->globalExpenses->values()->where('particular_id', $sub_head['id'])->values();

                                    if(!empty($expenses)) {
                                        echo ($expenses->sum('amount') > 0) ? number_format($expenses->sum('amount'), 2) : null;
                                    }
                                @endphp

                                {{-- {{ $voyage?->budgetInfo?->where('particular_id', $sub_head['id'])->where('port', null)->values()->first()?->expenses?->first()?->sum('amount') }} --}}

                            </td>
                        @endforeach


                    </tr>
                @endforeach
                @else
                    <tr>
                        <td>{{ $head['name'] }}</td>
                        <td></td>
                        <td>USD</td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>