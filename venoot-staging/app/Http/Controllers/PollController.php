<?php

namespace App\Http\Controllers;

use App\Poll;
use App\Question;
use App\Alternative;
use App\Answer;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Http\Request;
use App\Http\Requests\PollRequest;
use App\Participant;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show poll')) {
            return response()->json(['polls' => $user->company->polls], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function eventIndex(Event $event)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show poll')) {
            $polls = $event->polls()->with(['questions' => function ($query) {
                $query->with('answers');
            }])->get()->sortByDesc('pivot.created_at')
                ->groupBy('pivot.poll_id')->transform(function ($grouped_polls, $key) use ($event) {

                    $poll = $grouped_polls->first();
                    $poll->sent = $grouped_polls->count();
                    $poll->sent_at = $grouped_polls->max('created_at')->toDateTimeString();

                    // Check complete and incomplete polls
                    $questions = $poll->questions();
                    $questions_count = $questions->count();
                    $poll->incomplete = 0;
                    $poll->complete = 0;
                    Answer::whereIn('question_id', $questions->pluck('id'))->get()
                        ->groupBy('participant_id')
                        ->each(function ($answers) use ($poll, $questions_count) {
                            if ($answers->count() > 0 && $answers->count() < $questions_count) {
                                $poll->incomplete++;
                            } else if ($answers->count() == $questions_count) {
                                $poll->complete++;
                            }
                        });

                    return $poll;
                })->values();

            // $participants = $event->profile->participants;

            // foreach ($polls as $poll) {
            //     $fields = ['Nombre' => 'full_name'];
            //     $i = 0;
            //     foreach ($poll->questions as $question) {
            //         ++$i;
            //         $fields[$question->question] = 'question' . $i;
            //     }
            //     $poll->fields = $fields;

            //     $dataForExcel = [];
            //     foreach ($participants as $participant) {
            //         $e = ['full_name' => $participant['data']['name'] . " " . $participant['data']['lastname']];

            //         $j = 0;
            //         foreach ($poll->questions as $question) {
            //             ++$j;

            //             $answer = Answer::where('participant_id', $participant->id)->where('question_id', $question->id)->first();
            //             if ($answer) {
            //                 switch ($question->type) {
            //                     case 0:
            //                         $alternative = Alternative::find($answer->alternative_id)->alternative;
            //                         $e['question' . $j] = $alternative;
            //                         break;

            //                     case 1:
            //                         $alternatives = Alternative::whereIn('id', $answer->alternative_ids)->pluck('alternative')->toArray();
            //                         $e['question' . $j] = join("\n", $alternatives);
            //                         break;

            //                     case 2:
            //                         $e['question' . $j] = $answer->text;
            //                         break;
            //                 }
            //             }
            //         }
            //         array_push($dataForExcel, $e);
            //     }
            //     $poll->dataForExcel = $dataForExcel;
            // }

            return response()->json(['polls' => $polls]);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PollRequest $request)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('edit poll')) {
            $poll = Poll::create([
                'name' => $request->name,
                'description' => $request->description,
                'company_id' => $user->company->id,
            ]);

            foreach ($request->questions as $q) {
                $question = $poll->questions()->create($q);

                foreach ($q['alternatives'] as $alternative) {
                    $question->alternatives()->create($alternative);
                }
            }

            return response()->json(['polls' => $user->company->polls], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Poll $poll)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show poll')) {
            return response()->json(['poll' => $poll], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function externalGet(Event $event, Poll $poll)
    {
        #TODO CREATE PUBLISH LINK
        return response()->json(['poll' => $poll, 'event' => ['name' => $event->name]], 200);
    }

    public function checkUUID(Event $event, Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid'
        ]);

        $participation = $event->participations->filter(function ($participation) use ($request) {
            return $participation->uuid == $request->uuid;
        })->first();


        if ($participation) {
            $questions_with_answers = Poll::find($request->poll_id)->questions()
                ->with(['answers' => function ($query) use ($participation) {
                    $query->where('answers.participant_id', $participation->participant->id);
                }])->get();

            return response()->json(['user' => $participation->participant, 'questions' => $questions_with_answers], 200);
        } else {
            $questions_with_answers = null;

            return response()->json(['user' => null, 'questions' => $questions_with_answers], 200);
        }
    }

    public function participantFromPoll(Event $event, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'lastname' => 'required|string|max:30',
            'email' => [
                'required',
                Rule::unique('participants', 'data->email')->where(function ($query) use ($event) {
                    return $query->where('database_id', $event->profile->database->id);
                })
            ],
        ]);

        $participant = $event->profile->database->participants()->create(['data' => $request->only(['name', 'lastname', 'email'])]);
        $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
        $participation->save();

        return response()->json(['uuid' => $participation->uuid], 200);
    }

    public function answer(Event $event, Poll $poll, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:participants,id',
            'id' => 'bail|required|exists:questions',

            'type' => 'required|integer|in:0,1,2',

            'answer' => 'required|array',
            'answer.id' => 'sometimes:exists:answers',
            'answer.alternative' => [
                'required_if:type,0|integer',
                Rule::exists('alternatives', 'id')->where(function ($query) use ($request) {
                    return $query->where('question_id', $request->id);
                })
            ],
            'answer.alternatives' => [
                'required_if:type,1|array'
            ],
            'answer.alternatives.*' => [
                'integer',
                Rule::exists('alternatives', 'id')->where(function ($query) use ($request) {
                    return $query->where('question_id', $request->id);
                })
            ],
            'answer.text' => 'required_if:type,2|string'
        ]);

        $answer = Answer::where('participant_id', $request->user_id)->where('question_id', $request->id)->first();
        if (!$answer) {
            $answer = new Answer();
            $answer->participant_id = $request->user_id;
            $answer->question_id = $request->id;
        }

        switch ($request->type) {
            case 0:
                $answer->alternative_id = $request->answer['alternative'];
                break;

            case 1:
                $answer->alternative_ids = $request->answer['alternatives'];
                break;

            case 2:
                $answer->text = $request->answer['text'];
                break;
        }
        $answer->save();

        return response()->json(['id' => $answer->id], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PollRequest $request, Poll $poll)
    {
        $user = Auth::user();
        if (Gate::allows('edit-poll', $poll)) {
            $poll->name = $request->name;
            $poll->description = $request->description;
            $poll->save();

            $questions = collect($request->questions);
            $question_ids = $questions->pluck('id')->toArray();
            $poll->questions()->whereNotIn('id', $question_ids)->delete();

            foreach ($questions as $q) {
                $question = $poll->questions()->updateOrCreate(['id' => $q['id']], $q);
                $question->save();
                $question_ids[] = $question->id;

                $alternatives = collect($q['alternatives']);
                $alternative_ids = $alternatives->pluck('id')->toArray();
                $question->alternatives()->whereNotIn('id', $alternative_ids)->delete();

                foreach ($alternatives as $a) {
                    $alternative = $question->alternatives()->updateOrCreate(['id' => $a['id']], $a);
                    $alternative->save();
                    $alternative_ids[] = $alternative->id;
                }
            }

            return response()->json(['polls' => $user->company->polls, "ids" => $alternative_ids], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poll $poll)
    {
        $user = Auth::user();
        if (Gate::allows('edit-poll', $poll)) {
            $poll->delete();
            return response()->json(['polls' => $user->company->polls], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }
}
