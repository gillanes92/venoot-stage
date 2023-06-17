<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Venoot QR Default Template</title>
    <style>
        * {
            margin: 0px;
        }
    </style>
</head>

<body>
    <table style="margin: 0px auto; text-align: center; height: 224px;">
        <tr>
            <td style="text-align: center; padding-top: 10px;">
                <img src="data:image/png;base64, {!! base64_encode(
                    QrCode::format('png')->size($data->qrSize)->margin(0)->generate($participation->participant->data['email']),
                ) !!} " alt="CodigoQR" title="CodigoQR" />
            </td>
        </tr>
        <tr>
            <td>
                <div style="padding-top: 30px;">
                    <div style="font-size: 22px;">{{ $participation->participant->data['name'] }} {{ $participation->participant->data['lastname'] }}</div>
                    <div style="font-size: 20px;">{{ isset($participation->participant->data['job']) ? $participation->participant->data['job'] : '' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <script type="text/javascript">
        print('true')
    </script>
</body>

</html>
