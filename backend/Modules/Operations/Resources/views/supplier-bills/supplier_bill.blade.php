<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier Bill</title>
</head>
<body>
    <table>
        <tr>
            <td colspan="3" rowspan="3"></td>
            <td colspan="6" style="text-align: center; 
            font-size:20px; 
            padding:10px;"><h2>SUPPLIER BILL {{(($bunker_bill['company'])? 'OF '. (ucwords($bunker_bill['company'])): 'OF TOGGI SHIPPING')}}</h2></td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center;">{{ html_entity_decode($companyName, ENT_QUOTES, 'UTF-8') }}</td>
            <td style="text-align: right;">Date:</td>
            <td colspan="2">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td style="text-align: right;">Time:</td>
            <td colspan="2">{{ now()->format('g:i A') }}</td>
        </tr>

        @if(isset($bunker_bill['data']))
            <tr>
                <td colspan="3"><strong>Issue Date</strong></td>
                <td  colspan="3"><strong>Vendor</strong></td>
                <td colspan="3"><strong>Bill No.</strong></td>
                <td colspan="3"><strong>Remarks</strong></td>
            </tr>
            <tr>
                <td colspan="3">{{ date("d-M-Y", strtotime($bunker_bill['data']->date)) }}</td>
                <td colspan="3">{{ $bunker_bill['data']?->scmVendor?->name }}</td>
                <td colspan="3">{{ $bunker_bill['data']?->vendor_bill_no }}</td>
                <td colspan="3">{{ $bunker_bill['data']?->remarks }}</td>
            </tr>

            @if(count($bunker_bill['data']->opsBunkerBillLines) > 0)
                <tr>
                    <td colspan="4"><strong>PR NO.</strong></td>
                    <td colspan="4"><strong>Description</strong></td>
                    <td colspan="4"><strong>Amount</strong></td>
                </tr>
                @foreach($bunker_bill['data']->opsBunkerBillLines as $bill_line)
                    <tr>
                        <td colspan="4">{{$bill_line->pr_no}}</td>
                        <td colspan="4">{{$bill_line->description}}</td>
                        <td colspan="4">{{$bill_line->amount}}</td>
                    </tr>
                @endforeach
            @endif
        @else
            <tr></tr>
            <tr>
                <td colspan="12" style="text-align: center;">No Data Found...</td>
            </tr>
        @endif




    </table>
    
</body>
</html>