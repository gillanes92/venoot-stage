<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <table>
        <tr>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">MAIL</td>
            <td width="35" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">FECHA CARGA</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">№ EVENTOS INVITADOS</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">№ EVENTOS CONFIRMADOS</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">№ EVENTOS ASISTIDOS</td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border: 2px solid #000000; ">{{ $participant->data['name'] }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $participant->data['lastname'] }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $participant->data['email'] }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $participant->created_at }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $participant->participations()->whereNotNull('confirmed_sent_at')->count() }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $participant->participations()->where('confirmed_status', true)->count() }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $participant->participations()->whereNotNull('registered_at')->count() }}</td>
        </tr>
    </table>


    <table>
        <thead>
            <tr>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">NOMBRE EVENTO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">BBDD/PERFIL</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA EVENTO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA CARGA</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA ENVIO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA LECTURA</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA VISTA EVENTO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">ESTADO CONFIRMACION</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA CONFIRMADO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA ACREDITADO</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">FECHA APP</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">LLAVES</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">VENTAS</th>
                <th width="30" style="font-size: 12px; border: 2px solid #000000;">ENCUESTAS</th>
            </tr>
        </thead>
        <tbody>
        @foreach($databases as $database)
            @foreach($database->events as $event)
                <?php $participation = $participant->participations()->where('event_id', $event->id)->first(); ?>
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $database->name }} / {{ $event->profile->name }}</td>
                    <td>{{ $event->start_date }}</td>
                    @if(isset($participation))
                        <td>{{ $participation->created_at }}</td>
                        <td>{{ $participation->confirmed_sent_at }}</td>
                        <td>{{ $participation->view_mail_date }}</td>
                        <td></td>
                        <td>{{ $participation->confirmed_at ? ($participation->confirmed_status ? "CONFIRMO" : "RECHAZO") : ""  }}</td>
                        <td>{{ $participation->confirmed_at }}</td>
                        <td>{{ $participation->registered_at }}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</html>
