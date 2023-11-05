<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vessel Particulars</title>
</head>
<body>
    <h2>VESSEL PARTICULARS</h2>
    <table>
        <tr>
            <td>
                <strong>BASHUNDHARA EMPRESS</strong>
            </td>
            <td>
                <strong>{{$vesselParticulars->opsVessel->name}}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>OWNER</strong>
            </td>
            <td>
                {{$vesselParticulars->opsVessel->owner_name}}
            </td>
        </tr>
        <tr>
            <td>
                <strong>MANAGER/OPERATOR</strong>
            </td>
            <td>
                {{$vesselParticulars->opsVessel->manager}}
            </td>
        </tr>
        <tr>
            <td>
                <strong>CLASSIFICATION</strong>
            </td>
            <td>
                {{$vesselParticulars->opsVessel->classification}}
            </td>
        </tr>
        <tr>
            <td>
                <strong>FLAG</strong>
            </td>
            <td>
                <strong>{{$vesselParticulars->opsVessel->flag}}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>PORT OF REGISTRY</strong>
            </td>
            <td>
                <strong>{{$vesselParticulars->opsVessel->port_of_registry}}</strong>
            </td>
        </tr>
    </table>
</body>
</html>