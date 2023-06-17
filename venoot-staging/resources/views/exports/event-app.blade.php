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
        {{-- 2 a 5 --}}
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        {{-- 6 --}}
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">Contactos</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">Bajo el APP</td>
        </tr>
        {{-- 7 --}}
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #BDD7EE;">{{ $participant_count }}</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #FFD966;">{{ $app_count }}</td>
        </tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="6" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">Efectividad</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">BBDD / Completas</td>
            <td colspan="3" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #8EA9DB;">{{ 100 * $app_count / $participant_count }}%</td>
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
            <td style="font-size: 14px; background-color: #E7E6E6;">App Bajadas</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $app_count }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $event->estimate->mailings_quantity }}</td>
            <td style="font-size: 14px; text-align: center; background-color: #E7E6E6;">{{ $event->estimate->mailings_quantity > 0 ? 100 * $app_count / $event->estimate->mailings_quantity . "%" : "N/A" }}</td>
        </tr>

        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td style="font-size: 14px; font-weight: bold;">Notas Agenda</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="font-size: 14px; font-weight: bold;">Agenda</td>
            <td style="font-size: 14px; font-weight: bold;">Notas</td>
            <td style="font-size: 14px; font-weight: bold;">Promedio</td>
        </tr>
        @foreach ($event->activities as $activity)
        <tr>
            <td></td>
            <td></td>
            <td style="font-size: 14px;">{{ $activity->name }}</td>
        </tr>
        @endforeach

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
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">BAJO APP</td>

            @if ($poll)
                @foreach($poll->questions as $question)
                    <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">{{ $question->question }}</td>
                @endforeach
            @endif
        </tr>

        @foreach ($participants as $participant)
        <tr>
            <td>{{ $participant->data['name'] }}</td>
            <td>{{ $participant->data['lastname'] }}</td>
            <td>{{ $participant->data['email'] }}</td>
            <td>{{ !!$participant->expo_token ? 'SI' : 'NO' }}</td>
            @if ($poll)
                @foreach($poll->questions as $question)
                    @if($question->type == 0)
                        <td>{{ $participation->participant->answers()->where('question_id', $question->id)->first()->alternative->alternative }}</td>
                    @elseif($question->type == 1)
                        <td>{{ $participation->participant->answers()->where('question_id', $question->id)->first()->alternatives->implode('alternative', ', ') }}</td>
                    @elseif($question->type == 2)
                        <td>{{ optional($participation->participant->answers()->where('question_id', $question->id)->first())->text }}</td>
                    @endif
                @endforeach
            @endif
        </tr>
        @endforeach
        <tr></tr>
    </table>

</html>
