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
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->profile->database->name }} / {{ $event->profile->name }}</td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->location }}</td>
            <td></td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;"></td>
        </tr>
        {{-- 2 a 4 --}}
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        {{-- 6 --}}
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">Contactos</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">Confirmados</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">{{ $participants->count() }}</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">{{ $confirmed->count() }}</td>
        </tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #A9D08E;">Acreditados en el Evento</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">Efectividad</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #A9D08E;">{{ $registered->count() }}</td>
            <td colspan="2" style="font-size: 14px; text-align: center;">Acred. / Confirmación</td>
            <td style="font-size: 14px; text-align: center;"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="font-size: 14px; text-align: center;">Acred. / Contactos</td>
            <td style="font-size: 14px; text-align: center;"></td>
        </tr>

        <tr></tr>
        <tr></tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Resumen Envíos Mailing</td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-size: 14px; font-weight: bold; text-align: center; background-color: #AEAAAA;">Contratados</td>
            <td style="font-size: 14px; font-weight: bold; text-align: center; background-color: #AEAAAA;">Tasa</td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #E7E6E6;">Cantidad de Emails Enviados</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $sent_mails->count() }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $event->estimate->mailings_quantity }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;"></td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #FFFFFF;">Cantidad de Vistos</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;">{{ $sent_mails->where('opens', '>', 0)->groupBy('recipient')->count() }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #E7E6E6;">Cantidad de Rebotados</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $sent_mails->where('bounced', true)->groupBy('recipient')->count() }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;"></td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;"></td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #FFFFFF;">Cantidad de Eliminados</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;">0</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
        </tr>

        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Veces Compartidos a RRSS</td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px;">Twitter</td>
            <td style="font-size: 14px; text-align: right;">{{ $event->twitter_shares }}</td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px;">Facebook</td>
            <td style="font-size: 14px; text-align: right;">{{ $event->facebook_shares }}</td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px;">Linkedin</td>
            <td style="font-size: 14px; text-align: right;">{{ $event->linkedin_shares }}</td>
        </tr>

        <tr></tr>
        <tr></tr>

        <tr>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Detalle BBDD</td>
        </tr>

        <tr>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">MAIL</td>

            @foreach($event->profile->database->fields as $field)
                @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                    <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">{{ strtoupper($field['name']) }}</td>
                @endif
            @endforeach

            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">REBOTADOS</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">VIO LANDING</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">DADO DE BAJA</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">VIO MAIL</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">CONFIRMO / COMPRO / NO ASISTE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">ACREDITADO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">LLAVE 1</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">LLAVE 2</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">LLAVE N</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">ABRIO APP</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">ENCUESTAS</td>
        </tr>

        @foreach ($participants as $participant)
        <?php $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null; ?>
            <tr>
                <td>{{ isset($participant->data['name']) ? $participant->data['name'] : ''}}</td>
                <td>{{ isset($participant->data['lastname']) ? $participant->data['lastname'] : '' }}</td>
                <td>{{ $participant->data['email'] }}</td>
                @foreach($event->profile->database->fields as $field)
                    @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                        @if (array_key_exists($field['key'], $participant->data))
                            <td>{{ $participant->data[$field['key']] }}</td>
                        @else
                            <td></td>
                        @endif
                    @endif
                @endforeach
                <td></td>
                <td></td>
                <td></td>
            @if ($participation)
                <td>{{ $participation->view_mail_count > 0 ? "SI (". $participation->view_mail_count . " veces)" : "NO" }}</td>
                <td>{{ $participation->confirmed_at ? ($participation->confirmed_status ? $participation->confirmed_at : "RECHAZO (" . $participation->confirmed_at . ")") : ""  }}</td>
                <td>{{ $participation->registered_at }}</td>
            @else
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
    </table>

</html>
