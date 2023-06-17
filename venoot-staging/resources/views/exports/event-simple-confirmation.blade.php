<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <table>
        {{-- 1 --}}
        <tr>
            {{-- A - C --}}
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->name }}</td>
            <td></td>
            <td></td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->start_date }}</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->profile->database->name }}</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->location }}</td>
            <td></td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;"></td>
        </tr>
        {{-- 2 a 5 --}}
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>

        {{-- 6 --}}

        <tr>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Detalle BBDD</td>
        </tr>

         <tr>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">MAIL</td>

            @foreach($fields as $field)
                @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                    <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">{{ strtoupper($field['name']) }}</td>
                @endif
            @endforeach

            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">CONFIRMADO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">ACREDITADO</td>
        </tr>

        @foreach ($participations as $participation)
        
        <tr>
            <td>{{ $participation->participant->data['name'] }}</td>
            <td>{{ $participation->participant->data['lastname'] }}</td>
            <td>{{ $participation->participant->data['email'] }}</td>

            @foreach($fields as $field)
                @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                    @if (array_key_exists($field['key'], $participation->participant->data))
                        <td>{{ $participation->participant->data[$field['key']] }}</td>
                    @else
                        <td></td>
                    @endif
                @endif
            @endforeach

            <td>{{ $participation->confirmed_at ? ($participation->confirmed_status ? $participation->confirmed_at : "RECHAZO (" . $participation->confirmed_at . ")") : ""  }}</td>
            <td>{{ $participation->registered_at }}</td>
        </tr>

        @endforeach

    </table>

</html>
