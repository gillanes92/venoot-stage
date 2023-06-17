<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
// use App\ArtistCategory;
// use App\Answer;
// use App\Message;

class AppDataResource extends JsonResource
{
    public function toArray($request)
    {
        $agenda = [];
        $actividades = [];
        $acts = array();
        $activities = $this->event->activities()->orderBy('date', 'asc')->orderBy('start_time', 'asc')->get();

        if (count($activities) > 0) {
            foreach ($activities as $activity) {
                $activity->year = Carbon::parse($activity->date)->format('Y');
                $activity->month = Carbon::parse($activity->date)->format('m');
                $activity->day = Carbon::parse($activity->date)->format('d');
                array_push($acts, $activity);

                $act = [
                    'id_agenda' => $activity->id,
                    'id_artista' => optional($activity->actors()->first())->id,
                    'inicio' => $activity->start_time,
                    'fin' => $activity->end_time,
                    'descripcion' => $activity->name,
                    'texto' => $activity->description,
                    'salon' => $activity->location,
                    'fecha' => Carbon::parse($activity->date)->format('Y-m-d'),
                    'link' =>  "" //$activity->link
                ];
                $actividades[$activity->id] = $act;
            }
            foreach ($acts as $activity) {
                $agenda['indice'][$activity->year][$activity->month][$activity->day]['selected'] = false;
                $agenda['indice'][$activity->year][$activity->month][$activity->day]['activities'][] = $activity->id;
            }

            $initial_year = key($agenda['indice']);
            $initial_month = key($agenda['indice'][$initial_year]);
            $initial_day = key($agenda['indice'][$initial_year][$initial_month]);

            $agenda['indice'][$initial_year][$initial_month][$initial_day]['selected'] = true;
            $agenda['initialState'] = $agenda['indice'][$initial_year][$initial_month][$initial_day]['activities'];
            $agenda['indice'] = collect($agenda['indice']);
            $agenda['actividades'] = collect($actividades);
        }

        $artistas = [];
        foreach ($this->event->actors as $artist) {
            $artista = [
                'id_artista' => $artist->id,
                'nombre' => $artist->prefix . " " . $artist->name . " " . $artist->lastname,
                'apellido' => " ", # $artist->lastname,
                'foto' => "https://elasticbeanstalk-us-west-2-373134702977.s3-us-west-2.amazonaws.com/public/" . $artist->photo,
                'curriculum' => $artist->description,
                'web' => $artist->web,
                'facebook' => $artist->facebook,
                'twitter' => $artist->twitter,
                'instagram' => $artist->instagram,
                'youtube' => $artist->youtube,
                'linkedin' => $artist->linkedin,
                'tipo_artista' => $artist->category,
            ];
            array_push($artistas, $artista);
        }
        // usort(
        //     $artistas, function ($a, $b) {
        //         return strcmp($a['apellido'], $b['apellido']);
        //     }
        // );

        $auspiciadores = [];
        foreach ($this->event->sponsors as $sponsor) {
            if (!isset($auspiciadores[$sponsor->category])) {
                $auspiciadores[$sponsor->category] = [];
            }
            $auspiciador = [
                'id_auspiciadores' => $sponsor->id,
                'foto' => "https://elasticbeanstalk-us-west-2-373134702977.s3-us-west-2.amazonaws.com/public/" . $sponsor->logo,
                'tipo' => $sponsor->category,
            ];
            array_push($auspiciadores[$sponsor->category], $auspiciador);
        }

        $proximos = [];
        $i = 0;
        foreach ($this->event->landing->images as $image) {
            $externo = [
                'id' => $i,
                'banner' => "https://elasticbeanstalk-us-west-2-373134702977.s3-us-west-2.amazonaws.com/public/" . $image,
                'link' => 'http://facebook.com',
            ];
            array_push($proximos, $externo);
            $i++;
        }

        // $ratings = [];
        // foreach ($this->event->ratings as $rating) {
        //     $ratings[$rating->activity_id] = $rating->rating;
        // }
        // $ratings = collect($ratings);

        // $encuestas = [];
        // foreach ($this->event->pools as $pool) {
        //     if (!$pool->published || Answer::where('participant_id', $this->event->id)->where('pool_id', $pool->id)->first()) {
        //         continue;
        //     }

        //     $p = [
        //         'id_encuesta' => $pool->id,
        //         'titulo' => $pool->title,
        //         'publicada' => false,
        //         'preguntas' => [],
        //     ];

        //     foreach ($pool->questions as $question) {
        //         $q = [
        //             'id_pregunta' => $question->id,
        //             'tipo' => $question->type,
        //             'pregunta' => $question->question,
        //             'obligatorio' => 'S',
        //             'respuesta' => '',
        //             'done' => 0,
        //             'alternativas' => [],
        //         ];

        //         foreach ($question->alternatives as $alternative) {
        //             $a = [
        //                 'id_alternativa' => $alternative->id,
        //                 'alternativa' => $alternative->alternative,
        //                 'selected' => 0
        //             ];
        //             array_push($q['alternativas'], $a);
        //         }

        //         array_push($p['preguntas'], $q);
        //     }

        //     array_push($encuestas, $p);
        // }

        // $unread_messages = Message::where('reciever_type', 'par')->where('sender_type', 'par')->where('reciever_id', $this->event->id)->where('retrieved', false)->count();
        // $exe_unread_messages = Message::where('reciever_type', 'par')->where('sender_type', 'eje')->where('reciever_id', $this->event->id)->where('retrieved', false)->count();

        // if ($this->event->group) {
        //     $group_unread_messages = $this->event->group->groupMessages()->where('participant_id', '!=', $this->event->id)->whereDoesntHave(
        //         'groupMessageReads',
        //         function ($query) {
        //             $query->where('participant_id', $this->event->id);
        //         }
        //     )->count();
        // } else {
        //     $group_unread_messages = 0;
        // }


        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->event->start_date . " " . $this->event->start_time);
        $date_end = Carbon::createFromFormat('Y-m-d H:i:s', $this->event->end_date . " " . $this->event->end_time);

        return [
            'evento' => [
                'id' => $this->participant->id,
                'id_evento' => $this->event->id,
                'nombre' => $this->participant->data['name'],
                'apellido' => $this->participant->data['lastname'],
                'correo' => $this->participant->data['email'],
                'cargo' => null, //$this->event->job,
                'empresa' => null, //$this->event->company->social_reason,
                'show_on_chat' => false, //$this->event->on_chat,
                'networking' => $this->networking,
                'cod_barra' => null, //$this->event->uid,
                'exe_id' => optional(optional($this->event->client)->executive)->id,
                'exe_name' => optional(optional($this->event->client)->executive)->name,
                'titulo' => $this->event->name,
                'banner' => "https://elasticbeanstalk-us-west-2-373134702977.s3-us-west-2.amazonaws.com/public/" . $this->event->banner,
                'descripcion' => $this->event->description,
                'pdf' => null, //$this->event->more_information,
                'fecha' => $date->format('d/m/Y'),
                'hora' => $date->format('H:i'),
                'fecha_fin' => $date_end->format('d/m/Y'),
                'hora_fin' => $date_end->format('H:i'),
                'facebook' => $this->event->facebook,
                'twitter' => $this->event->twitter,
                'youtube' => $this->event->youtube,
                'instagram' => $this->event->instagram,
                'longitude' => (float) -70.59838,
                'latitude' => (float) -33.413897,
                'direccion' => $this->event->address,
                'os' => $this->event->os,
                'test_user' => false,
                // 'unread_messages' =>  $unread_messages,
                // 'exe_unread_messages' => $exe_unread_messages,
                // 'group_unread_messages' => $group_unread_messages,
                'group' => $this->event->group,
                'has_calendar' => $this->event->has_calendar,
                'availableWindows' => $this->event->availableWindows,
                'localization' => $this->event->languages,
            ],

            'agenda' => $agenda,
            'artistas' => $artistas,
            'auspiciadores' => $auspiciadores,
            'proximos' => $proximos,
            // 'ratings' => $ratings,
            // 'encuestas' => $encuestas,

            // 'message' => $message,
        ];
    }
}
