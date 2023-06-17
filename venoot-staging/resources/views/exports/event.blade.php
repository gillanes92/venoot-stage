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
            <td colspan="4" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">Contactos</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">{{ $participants->count() }}</td>
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
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;">{{ $sent_mails->where('opens', '>', 0)->count() }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #E7E6E6;">Cantidad de Rebotados</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $sent_mails->where('bounced', true)->count() }}</td>
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
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>

        <tr>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Detalle BBDD</td>
        </tr>

        <tr>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">MAIL</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">TIPO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">FECHA ENVIO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">VIO MAIL</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">REBOTADOS</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">DADO DE BAJA</td>
        </tr>

        @foreach ($participants as $participant)
        <?php $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null; ?>
            @if (!$participation)
            <tr>
                <td>{{ $participant->data['name'] }}</td>
                <td>{{ $participant->data['lastname'] }}</td>
                <td>{{ $participant->data['email'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @else
                <?php $participant_sent_mails = collect($participation['sent_mails']); ?>
                @if ($participant_sent_mails->count() == 0)
                    <tr>
                        <td>{{ $participant->data['name'] }}</td>
                        <td>{{ $participant->data['lastname'] }}</td>
                        <td>{{ $participant->data['email'] }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @else
                    @foreach($participant_sent_mails as $sent_mail)
                        <tr>
                            @if($loop->first)
                                <td>{{ $participant->data['name'] }}</td>
                                <td>{{ $participant->data['lastname'] }}</td>
                                <td>{{ $participant->data['email'] }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            <td>{{ $sent_mail->category }}</td>
                            <td>{{ $sent_mail->created_at }}</td>
                            <td>{{ $sent_mail->opens > 0 ? "SI (". $sent_mail->opens . " veces)" : "NO" }}</td>
                            <td>{{ $sent_mail->bounced ? 'SI' : 'NO' }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif
            @endif
        @endforeach
    </table>
</html>
