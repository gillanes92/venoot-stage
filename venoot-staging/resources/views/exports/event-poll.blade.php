<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <table>
        {{-- 1 --}}
        <tr>
            {{-- A - B --}}
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $event->name }}</td>
            <td></td>
            <td style="font-size: 16px; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: center;">{{ $poll->name }}</td>
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
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">Encuestas Completas</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">{{ $event->profile->participants->count() }}</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">{{ $poll->complete }}</td>
        </tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="6" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">Efectividad</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">Contactos / Completas</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">{{ 100 * $poll->complete / $event->profile->participants->count() }}%</td>
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
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $event->sent_emails()->count() }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $event->estimate->mailings_quantity }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;"></td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #FFFFFF;">Cantidad de Vistos</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;">{{ $event->sent_emails()->where('opens', '>', 0)->groupBy('recipient')->count() }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
            <td style="font-size: 14px; text-align: center; background-color: #FFFFFF;"></td>
        </tr>

        <tr>
            <td></td>
            <td style="font-size: 14px; background-color: #E7E6E6;">Cantidad de Rebotados</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $event->sent_emails()->where('bounced', true)->groupBy('recipient')->count() }}</td>
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

        @for($i = 0; $i < $endRowChart - 30; $i++)
            <tr><tr>
        @endfor

        <tr>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Detalle BBDD</td>
        </tr>

        <tr>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">MAIL</td>

            @foreach($poll->questions as $question)
                <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">{{ $question->question }}</td>
            @endforeach
        </tr>

        @foreach ($event->participations as $participation)
        <tr>
            <td>{{ $participation->participant->data['name'] }}</td>
            <td>{{ $participation->participant->data['lastname'] }}</td>
            <td>{{ $participation->participant->data['email'] }}</td>
            @foreach($poll->questions as $question)
                @if($participation->participant->answers()->where('question_id', $question->id)->first())
                    @if($question->type == 0)
                        <td>{{ $participation->participant->answers()->where('question_id', $question->id)->first()->alternative->alternative }}</td>
                    @elseif($question->type == 1)
                        <td>{{ $participation->participant->answers()->where('question_id', $question->id)->first()->alternatives->implode('alternative', ', ') }}</td>
                    @elseif($question->type == 2)
                        <td>{{ optional($participation->participant->answers()->where('question_id', $question->id)->first())->text }}</td>
                    @endif
                @else
                    <td></td>
                @endif
            @endforeach
        </tr>
        @endforeach
        <tr></tr>
    </table>

</html>
