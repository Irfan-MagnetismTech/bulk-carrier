<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BULK NOON REPORT</title>
    <style>
        table, tr, td, th
        {
            border: 1px solid #252525;
            border-collapse: collapse;
        }
    </style>
</head>
<body>    
    <table>
        <tr>
            <th colspan="11" style="text-align: center; 
            font-size:20px;
            padding:10px;"><h2>BULK NOON REPORT @if(isset($bulkNoonReport))- {{$bulkNoonReport->opsVessel->name}} @endif</h2></th>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center;">{{ html_entity_decode($companyName, ENT_QUOTES, 'UTF-8') }}</td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: right;">Date:</td>
            <td colspan="2">{{ now()->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: right;">Time:</td>
            <td colspan="2">{{ now()->format('g:i A') }}</td>
        </tr>


        <tr>
            <td colspan="11" style="text-align: center;">No data found...</td>
        </tr>
    </table>
</body>
</html>