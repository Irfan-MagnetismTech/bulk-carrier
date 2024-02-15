<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Month Wise Expense Report</title>
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
    <h2 style="text-align: center; margin: 15px auto; font-weight: 600; font-size: 1.7rem">
        {{-- Month Wise Expense Report --}}
    </h2>
    <table>
        <thead>
            <tr>
                <th>Voyage</th>
                <th style="width: 150px">Expense in Tk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($voyages as $voyage)
                <tr>
                    <td>
                        {{ $voyage->voyage_sequence }}
                    </td>
                    <td style="text-align: right;">
                        
                        {{ ($voyage->amount_bdt > 0 ) ? number_format($voyage->amount_bdt, 2) : null }}

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top: 20px;">
        <tbody>
            <tr>
                <th rowspan="2">Summary</th>
                <th>Total Voyage</th>
                <th>Cash Requisitions</th>
                <th>Total Expense</th>
                <th>Left Over Amount</th>
            </tr>
            <tr>
                <td style="text-align: center;">{{ count($voyages) }}</td>
                <td style="text-align: right;">{{ number_format($allCashRequisitions->sum('total_amount'), 2) }}</td>
                <td style="text-align: right;">{{ number_format($voyages->sum('amount_bdt'), 2) }}</td>
                <td style="text-align: right;">{{ ($allCashRequisitions->sum('total_amount') - $voyages->sum('amount_bdt') > 0 ) ? number_format($allCashRequisitions->sum('total_amount') - $voyages->sum('amount_bdt'), 2) : '('.number_format(abs($allCashRequisitions->sum('total_amount') - $voyages->sum('amount_bdt')), 2).')' }}</td>
            </tr>
        </tbody>
    </table>
</body>