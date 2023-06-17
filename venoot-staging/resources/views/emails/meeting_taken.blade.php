@component('mail::message')
# Reunion Agendada -  {{ $stand->name }}

**Fecha:** {{ $meeting->start }}
@if ($profile == 'participant')
**Host:** {{ $manager->name. ' ' . $manager->last_name  }}

@component('mail::button', ['url' => '$meeting->participant_link'])
UNIRSE
@endcomponent
@elseif($profile == 'host')
**Participante:** {{ $participant->data['name']. ' ' . $participant->data['lastname']  }}

@component('mail::button', ['url' => $meeting->manager_link])
UNIRSE
@endcomponent
@endif

Saludos,<br>
{{ $event->name }}
@endcomponent
