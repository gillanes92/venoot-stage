<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Answer;
use App\ChatParticipant;
use App\ChatActivityTime;
use App\Event;
use App\ParticipantQuestion;
use App\Participation;
use App\Participant;
use App\ParticipantTicket;
use App\Question;
use App\QuestionRequest;
use App\Events\LoginEvent;
use App\Events\LogoutEvent;
use App\Events\ParticipantQuestionEvent;
use App\Events\PollAnswerEvent;
use App\Events\PollQuestionEvent;
use App\Events\StreamChatMessageEvent;
use App\Events\StreamPrivateChatMessageEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class StreamingController extends Controller
{
  public function login(Request $request)
  {
    try {
      $request->validate([
        'activity_id' => 'nullable|exists:activities,id',
        'uuid' => 'required|uuid'
      ]);
    } catch (ValidationException $e) {
      return response()->json(['error' => 'login_error'], 401);
    }

    $participation = Participation::where('uuid', $request->uuid)->first();
    if (!$participation) {
      $participant_ticket = ParticipantTicket::where('uuid', $request->uuid)->first();
      if ($participant_ticket && $participant_ticket->participant_order->status == 1) {
        $participation = Participation::firstOrNew(['event_id' => $participant_ticket->participant_order->event->id, 'participant_id' => $participant_ticket->participant->id], ['uuid' => (string) Str::uuid()]);
        $participation->confirmed_at = Carbon::now();
        $participation->confirmed_status = true;
        $participation->save();
      } else if (!$participant_ticket) {

        $client = new \GuzzleHttp\Client();
        $url = "https://venoot-pro.work/streaming/auth/login";

        $form_params = [
          'activity_id' => $request->activity_id,
          'uuid' => $request->uuid,
        ];

        try {
          $response = $client->post($url, ['verify' => false, 'form_params' => $form_params]);

          if ($response->getStatusCode() == 200) {
            $decoded_response = json_decode($response->getBody()->getContents());
            $remote_participation = $decoded_response->me;
            $remote_participant = $decoded_response->me->participant;
            $event = Event::find(1);
            $local_participation = $event->participations()->whereHas('participant', function ($query) use ($remote_participant) {
              $query->whereJsonContains('data->email', $remote_participant->data->email);
            })->first();

            if (!$local_participation) {
              $participant = $event->profile->database->participants()->create(collect($remote_participant)->toArray());
              $participation_array = collect($remote_participation)->toArray();
              unset($participation_array['id']);
              $participation_array['participant_id'] = $participant->id;
              $participation = $event->participations()->create($participation_array);
            }
          }
        } catch (\Exception $e) {
          $response = null;
          // return response()->json($e->getMessage(), 503);
        }
      }
    }

    if ($participation) {
      $participation->chat_participants()->firstOrCreate([
        'event_id' => $participation->event_id,
        'activity_id' => $request->activity_id
      ], [
        'name' => $participation->participant->data['name'] . " " . $participation->participant->data['lastname']
      ]);

      $token = $this->guard()->login($participation);
      $participation->registered_at = Carbon::now();
      $participation->token = 'Bearer ' . $token;
      $participation->save();

      return response()->json(['status' => 'success', 'me' => $participation], 200)->header('Authorization', $token);
    }

    return response()->json(['error' => 'login_error'], 401);
  }

  public function loginByEmail(Event $event, Request $request)
  {
    try {
      $request->validate([
        'email' => 'required|email'
      ]);
    } catch (ValidationException $e) {
      return response()->json(['error' => 'login_error'], 401);
    }

    $participation = $event->participations()->with(['participant' => function ($query) use ($request) {
      $query->whereJsonContains('data->email', $request->email);
    }])->first();

    if ($participation) {
      $token = $this->guard()->login($participation);
      $participation->registered_at = Carbon::now();
      $participation->token = 'Bearer ' . $token;
      $participation->save();

      return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
    }

    return response()->json(['error' => 'login_error'], 401);
  }

  public function logout(Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id',
    ]);

    $participation = $this->guard()->user();
    $participation->registered_at = Carbon::now();
    $participation->save();

    $chat_participants = ChatParticipant::where('participation_id', $participation->id);
    $chat_participant = $chat_participants->first();
    $chat_participant->delete();

    $this->guard()->logout();
    return response()->json([
      'status' => 'success',
    ], 200);
  }

  public function refresh(Request $request)
  {
    if ($request->header('Authorization') != $this->guard()->user()->token) {
      $this->guard()->invalidate();
      return response()->json(['error' => 'refresh_token_error'], 401);
    }

    if ($token = $this->guard()->refresh()) {
      $participation = $this->guard()->user();
      // $participation->registered_at = Carbon::now();
      $participation->token = 'Bearer ' . $token;
      $participation->save();
      return response()
        ->json(['status' => 'success'], 200)
        ->header('Authorization', $token);
    }

    // return response()->json(['error' => 'refresh_token_error'], 401);
    return response()->json(['error' => 'empty_request'], 200);
  }

  public function keepAlive(Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id'
    ]);

    $participation = $this->guard()->user();

    if ($request->filled('activity_id')) {
      $last_chat_activity_time = $participation->chat_activity_times()->where('activity_id',  $request->activity_id)->get()->last();
    } else {
      $last_chat_activity_time = $participation->chat_activity_times()->whereNull('activity_id')->get()->last();
    }

    if (!$last_chat_activity_time || $last_chat_activity_time->updated_at->diffInMinutes(Carbon::now()) > 5) {
      $last_chat_activity_time = $participation->chat_activity_times()->create([
        'activity_id' => $request->filled('activity_id') ? $request->activity_id : null,
      ]);
      $status = 'new';
    } else {
      $last_chat_activity_time->touch();
      $status = 'refresh';
    }

    return response()->json(['status' => $status], 200); //), 'new_messages' => $new_messages, 'new_question_requests' => $new_questions_requests], 200);
  }

  public function me()
  {
    return response()->json([
      'status' => 'success',
      'data' => $this->guard()->user()->load('participant'),
      200
    ]);
  }

  public function videoUrl(Request $request)
  {
    $request->validate([
      'event_id' => 'exists:events,id',
      'activity_id' => 'nullable|exists:activities,id',
    ]);

    $event = Event::find($request->event_id);

    if ($request->filled('activity_id')) {
      return response()->json(['videourl' => Activity::find($request->activity_id)->video_url, 'event_name' => $event->name, 'video_category' => $event->video_category], 200);
    }

    if ($request->filled('event_id')) {
      return response()->json(['videourl' => Event::find($request->event_id)->landing->video_url, 'event_name' => $event->name, 'video_category' => $event->video_category], 200);
    }

    return response()->json(['error' => 'video_url_not_found'], 404);
  }

  public function message(Participation $participation, Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id',
      'message' => 'required|json',
    ]);

    $chat_message = $participation->stream_chat_messages()->create([
      'event_id' => $participation->event_id,
      'activity_id' => $request->activity_id,
      'message_data' => is_string($request->message) ? json_decode($request->message) : $request->message,
    ]);

    $participation->all_stream_chat_messages()->syncWithoutDetaching($chat_message);

    if ($request->activity_id) {
      broadcast(new StreamChatMessageEvent('event-' . $chat_message->event_id . '-activity-' . $chat_message->activity_id, $chat_message->message_data))->toOthers();
    } else {
      broadcast(new StreamChatMessageEvent('event-' . $chat_message->event_id, $chat_message->message_data))->toOthers();
    }

    return response()->json(['status' => 'success'], 200);
  }

  public function privateMessage(Participation $sender, Participation $receiver, Request $request)
  {
    $request->validate([
      'message' => 'required|array',
      'message.id' => 'required|exists:participations,id',
      'message.type' => 'required|string',
      'message.text' => 'required|string',
      'message.name' => 'required|string',
      'message.uuid' => 'required|uuid'
    ]);

    broadcast(new StreamPrivateChatMessageEvent('participant-' . $receiver->id, $request->message));
    return response()->json(['status' => 'success'], 200);
  }

  public function activeParticipants(Event $event)
  {
    $chat_activity_times = ChatActivityTime::whereIn('participation_id', $event->participations->pluck('id'))->where('updated_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())->get();
    $chat_activity_times = $chat_activity_times->unique('participation_id');

    return response()->json(['participant_count' => $chat_activity_times->count()], 200);
  }

  public function sendQuestion(Participation $participation, Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id',
      'question_request_id' => 'required|exists:question_requests,id',
      'question' => 'required|string',
    ]);

    $submitted_questions_count = $participation->submitted_questions()
      ->where('activity_id', $request->filled('activity_id') ? $request->activity_id : null)
      ->where('question_request_id', $request->question_request_id)->count();
    $question_request = QuestionRequest::find($request->question_request_id);

    // if ($submitted_questions_count >= $question_request->per_person_question_limit) {
    //     return response()->json(['status' => 'max_personal_interactions_reached'], 403);
    // }

    // if ($question_request->submitted_questions()->count() >= $question_request->question_limit) {
    //     return response()->json(['status' => 'max_request_interactions_reached'], 403);
    // }

    $submitted_question = $participation->submitted_questions()->create([
      'activity_id' => $request->activity_id,
      'question_request_id' => $request->question_request_id,
      'question' => $request->question,
    ]);

    if ($request->activity_id) {
      broadcast(new ParticipantQuestionEvent('event-' . $participation->event_id . '-activity-' . $request->activity_id, $submitted_question));
    } else {
      broadcast(new ParticipantQuestionEvent('event-' . $participation->event_id, $submitted_question));
    }

    return response()->json(['status' => 'success'], 200);
  }

  public function voteQuestion(Participation $participation, Request $request)
  {
    $request->validate([
      'participant_question_id' => 'required|exists:participant_questions,id',
    ]);

    $participant_question = ParticipantQuestion::find($request->participant_question_id);
    $participant_question->votes = $participant_question->votes + 1;
    $participant_question->save();

    return response()->json(['status' => $participation, 'other' => $participant_question], 200);
  }

  public function sendPollQuestion(Event $event, Question $question, Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id',
    ]);

    $event->polls()->syncWithoutDetaching($question->poll);
    $event->stream_questions()->syncWithoutDetaching($question);

    if ($request->activity_id) {
      broadcast(new PollQuestionEvent('event-' . $event->event_id . '-activity-' . $request->activity_id, $question));
    } else {
      broadcast(new PollQuestionEvent('event-' . $event->event_id, $question));
    }

    return response()->json(['status' => 'success'], 200);
  }

  public function answerQuestion(Participation $participation, Question $question, Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id',
      'alternative_id' => 'sometimes|exists:alternatives,id',
      'alternative_ids' => 'sometimes|exists:alternatives,id',
      'text' => 'sometimes|string'
    ]);

    $answer = Answer::where('participant_id', $participation->participant->id)->where('question_id', $question->id)->first();
    if (!$answer) {
      $answer = new Answer();
      $answer->participant_id = $participation->participant->id;
      $answer->question_id = $question->id;
    }

    switch ($question->type) {
      case 0:
        $answer->alternative_id = $request->alternative_id;
        break;

      case 1:
        $answer->alternative_ids = $request->alternative_ids;
        break;

      case 2:
        $answer->text = $request->text;
        break;
    }

    $answer->save();

    $summary = [];
    switch ($question->type) {
      case 0:
        $summary = [
          'id' => $question->id,
          'question' => $question->question,
          'alternatives' => $question->alternatives,
          'answers' => $question->answers->countBy('alternative_id'),
        ];
        break;

      case 1:
        $summary = [
          'id' => $question->id,
          'question' => $question->question,
          'alternatives' => $question->alternatives,
          'answers' => $question->answers->countBy('alternative_ids'),
        ];
        break;
    }

    if ($request->activity_id) {
      broadcast(new PollAnswerEvent('event-' . $participation->event_id . '-activity-' . $request->activity_id, $summary));
    } else {
      broadcast(new PollAnswerEvent('event-' . $participation->event_id, $summary));
    }

    return response()->json(['status' => 'success'], 200);
  }

  public function getAnswers(Event $event)
  {
    $questions = $event->stream_questions()->with('answers')->get();
    $summary = [];
    $i = 0;
    foreach ($questions as $question) {

      switch ($question->type) {
        case 0:
          $summary[$i] = [
            'question' => $question->question,
            'alternatives' => $question->alternatives,
            'answers' => $question->answers->countBy('alternative_id'),
          ];
          break;

        case 1:
          $summary[$i] = [
            'question' => $question->question,
            'alternatives' => $question->alternatives,
            'answers' => $question->answers->countBy('alternative_ids'),
          ];
          break;
      }


      $i++;
    }

    return response()->json(['answers' => $summary], 200);
  }

  public function eventData()
  {
    $event = $this->guard()->user()->event;
    return response()->json(['name' => $event->name, 'logo' => $event->logo_event, 'activities' => $event->activities()->where('show_in_landing', true)->get()->sortBy('start_time')->sortBy('date')->groupBy('date'), 'stands' => $event->stands()->with('manager')->get()], 200);
  }

  public function showActivity(Request $request)
  {
    $request->validate([
      'activity_id' => 'nullable|exists:activities,id'
    ]);

    $event = $this->guard()->user()->event;
    $speaker = Participation::where('uuid', $event->uuid)->first();

    $activity = Activity::find($request->activity_id);



    return response()->json(['activity' => $activity, 'event_name' => $event->name, 'activities' => $event->activities()->where('show_in_landing', true)->orderBy('order', 'desc')->orderBy('start_time', 'asc')->get()->groupBy('date'), 'sponsors' => $event->sponsors, 'speaker' => $speaker ? $speaker->id : null, 'shared_chat' => $event->shared_chat, 'one_to_one_chat' => $event->one_to_one_chat, 'speaker_chat' => $event->speaker_chat, 'primary' => $event->primary, 'secondary' => $event->secondary, 'terciary' => $event->terciary], 200);
  }

  public function venootChat()
  {
    return view('chat');
  }

  private function guard()
  {
    return Auth::guard('streaming');
  }
}
