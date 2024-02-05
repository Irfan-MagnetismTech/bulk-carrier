<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget vs Expense Report</title>
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
                <th></th>
                @foreach($voyages as $voyage)
                <th colspan="2">{{ $voyage->voyage_sequence }}</th>
                @endforeach
            </tr>
            <tr>
                <th>Head</th>
                {{-- <th>Currency</th> --}}
                @foreach($voyages as $voyage)
                <th>Budget</th>
                <th>Actual</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach($heads as $head)
                <tr>
                    <td><nobr>{{ $head->name }}</nobr></td>
                    {{-- <td></td> --}}
                    @foreach($voyages as $voyage)
                    <td style="text-align: right;">
                        @php
                            $amountBdt =  $voyage?->opsVoyageBudget?->opsVoyageBudgetEntries->where('ops_expense_head_id', $head->id)->sum('amount_bdt');
                        @endphp

                        {{ ($amountBdt > 0 ) ? number_format($amountBdt, 2) : null }}
                    </td>
                    <td style="text-align: right;">
                        @php
                            $amountBdt =  $expenseEntries->where('ops_expense_head_id', $head->id)
                                                        ->where('ops_voyage_id', $voyage->id)
                                                        ->sum('amount_bdt');
                        @endphp

                        {{ ($amountBdt > 0 ) ? number_format($amountBdt, 2) : null }}
                    </td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td><nobr>Fuel</nobr></td>
                @foreach($voyages as $voyage)
                    <td style="text-align: right;">
                        {{ ($voyage['amount_bdt'] > 0 ) ? number_format($voyage['amount_bdt'], 2) : null }}
                    </td>
                    <td style="text-align: right;">
                        {{ ($voyage['amount_bdt'] > 0 ) ? number_format($voyage['amount_bdt'], 2) : null }}
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
</html>