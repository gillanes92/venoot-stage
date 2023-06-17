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

        <tr>
            <td style="font-size: 14px; font-weight: bold; text-align: center;">Detalle BBDD</td>
        </tr>

        <tr>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">NOMBRE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">APELLIDO</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">MAIL</td>


            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">ACTIVIDAD</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">DESDE</td>
            <td style="font-size: 12px; font-weight: bold; border: 2px solid #000000;">HASTA</td>
        </tr>

        @foreach($participant_chat_activity_times as $participant_id => $chat_activity_times)
            @foreach ($chat_activity_times as $chat_activity_time)
            <tr>
                @if ($loop->first)
                    <td>{{ $chat_activity_time->participant->data['name'] }}</td>
                    <td>{{ $chat_activity_time->participant->data['lastname'] }}</td>
                    <td>{{ $chat_activity_time->participant->data['email'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                <td>{{ $chat_activity_time->activity_id == 0 ? "General" : $chat_activity_time->activity->name }}</td>
                <td>{{ $chat_activity_time->created_at }}</td>
                <td>{{ $chat_activity_time->updated_at }}</td>
            </tr>
            @endforeach
        @endforeach

        {{-- @foreach ($chat_times_consolidated as $participant_id => $activities)
            @foreach ($activities as $activity_id => $chat_time)
                <tr>
                    @if ($loop->first)
                        <td>{{ $chat_time['name'] }}</td>
                        <td>{{ $chat_time['lastname'] }}</td>
                        <td>{{ $chat_time['email'] }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    <td>{{ $chat_time['activity'] }}</td>
                    <td>{{ $chat_time['last_seen'] }}</td>
                    <td>{{ Carbon\CarbonInterval::seconds($chat_time['time'])->cascade()->forHumans() }}</td>
                </tr>
            @endforeach
        @endforeach --}}
    </table>
</html>
