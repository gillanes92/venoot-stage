<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <table>
        <tr>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">EVENTO</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">ENVIADOS</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">REBOTADOS</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">FECHA</td>
        </tr>
        <tr>
            <td style="font-size: 12px; border: 2px solid #000000; ">{{ $event->name }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $event->sent_emails()->count() }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $bounced_emails->count() }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $now }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">RECEPTOR</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">EMISOR</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">ASUNTO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">CATEGORíA</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">TIPO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">RAZóN</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA ENVIO</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($bounced_emails as $bounced)
            <tr>
                <td>{{ $bounced->recipient }}</td>
                <td>{{ $bounced->sender }}</td>
                <td>{{ $bounced->subject }}</td>
                <td>{{ $bounced->category }}</td>
                <td>{{ $bounced->bounce_type }}</td>
                <td>{{ $bounced->bounce_reason }}</td>
                <td>{{ $bounced->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</html>

