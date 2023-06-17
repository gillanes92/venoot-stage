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
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">Contactos</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">Acreditados en el EVento</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">{{ $participants->count() }}</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">{{ $registered->count() }}</td>
        </tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="6" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">Efectividad (Acred. / Contactos)</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;"></td>
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

            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">ORDEN</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">TICKET</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">DISCOUNT</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">COSTO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">TOTAL</td>
        </tr>

        @foreach ($event->paid_participant_orders as $order)
            @foreach($order->tickets_data->tickets as $ticket_data)
            <tr>
                @if ($loop->first)
                    <td>{{ is_array($order->participant->data['name']) ? join(",", $order->participant->data['name']) : $order->participant->data['name'] }}</td>
                    <td>{{ $order->participant->data['lastname'] }}</td>
                    <td>{{ $order->participant->data['email'] }}</td>

                    @foreach($event->profile->database->fields as $field)
                        @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                            @if (array_key_exists($field['key'], $order->participant->data))
                                <td>{{ $order->participant->data[$field['key']] }}</td>
                            @else
                                <td></td>
                            @endif
                        @endif
                    @endforeach

                    <td>{{ $order->number }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>

                    @foreach($event->profile->database->fields as $field)
                        @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                            <td></td>
                        @endif
                    @endforeach

                    <td></td>
                @endif
                <td>{{ $ticket_data->ticket->name }}</td>
                <td>{{ optional($ticket_data->discount)->name }}</td>
                <td>{{ $ticket_data->cost }}</td>
                @if ($loop->first)
                    <td>{{ $order->total }}</td>
                @endif
            </tr>
            @endforeach
        @endforeach
    </table>

</html>
