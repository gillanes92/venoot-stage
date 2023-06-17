<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">BASE DE DATOS</td>
            <td width="30" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">FECHA CREACION</td>
            <td width="35" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">CANTIDAD DE PERFILES</td>
            <td width="20" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">№ EVENTOS</td>
            <td width="60" style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">№ EVENTOS CON VENTAS O CONFIRMADOS</td>
            <td width="20" rowspan="2"></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $database->name }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $database->created_at }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $database->profiles()->count() }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">{{ $events->count() }}</td>
            <td style="font-size: 12px; border: 2px solid #000000;">
                {{ $events->filter(function ($event){
                        return $event->estimate->confirmation_form || $event->estimate->ticket_sale;
                    })->count()
                }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">MAIL</td>

            @foreach($database->fields as $field)
                @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                    <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">{{ strtoupper($field['name']) }}</td>
                @endif
            @endforeach

            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">UUID</td>
        </tr>

        @foreach ($database->participants as $participant)
            <tr>
                <td>{{ $participant->data['name'] }}</td>
                <td>{{ $participant->data['lastname'] }}</td>
                <td>{{ $participant->data['email'] }}</td>
                @foreach($database->fields as $field)
                    @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                        @if (array_key_exists($field['key'], $participant->data))
                            <td>{{ $participant->data[$field['key']] }}</td>
                        @else
                            <td></td>
                        @endif
                    @endif
                @endforeach
                <td>{{ array_key_exists('uuid', $participant->data) ? $participant->data['uuid'] : '' }}</td>
            </tr>
        @endforeach

        <tr></tr>

        <tr>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">NOMBRE EVENTO</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000;">MAIL</td>

            @foreach($database->fields as $field)
                @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                    <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">{{ strtoupper($field['name']) }}</td>
                @endif
            @endforeach
        </tr>

        @foreach($events as $event)
            @foreach ($event->profile->participants as $participant)
                <?php $participation = $participant->participations()->where('event_id', $event->id)->first(); ?>
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $participant->data['name'] }}</td>
                    <td>{{ $participant->data['lastname'] }}</td>
                    <td>{{ $participant->data['email'] }}</td>
                    @foreach($database->fields as $field)
                        @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                            @if (array_key_exists($field['key'], $participant->data))
                                <td>{{ $participant->data[$field['key']] }}</td>
                            @else
                                <td></td>
                            @endif
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    </table>
</html>
