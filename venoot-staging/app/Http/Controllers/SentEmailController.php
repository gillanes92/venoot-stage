<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\SentEmail;
use Illuminate\Http\Request;

class SentEmailController extends Controller
{
    public function handleWebhook(Request $request)
    {
        if (!array_key_exists('env', $request['event-data']['user-variables']) || $request['event-data']['user-variables']['env'] != config('app.env')) return;

        switch ($request['event-data']['event']) {
            case 'opened':
                $message = SentEmail::where('message_id', $request['event-data']['message']['headers']['message-id'])->first();
                if ($message) {
                    Log::info('Message ' . $message->message_id . ' opened!');
                    $message->touch();
                    $seen_at = $message->seen_at_emails()->create();
                    $seen_at->save();
                }
                break;

            case 'delivered':
                $mail = $request['event-data']['envelope']['targets'];
                Log::info('Message to mail' . $mail . ' delivered!');
                if (!DB::table('verified_mails')->where('email', strtolower($mail))->exists()) {
                    DB::table('verified_mails')->insert(['email' => strtolower($mail)]);
                }
                break;

            case 'failed':
                switch ($request['event-data']['severity']) {
                    case 'permanent':
                        $message = SentEmail::where('message_id', $request['event-data']['message']['headers']['message-id'])->first();
                        if ($message) {
                            Log::info('Message ' . $message->message_id . ' bounced!');
                            $message->bounced = true;
                            $message->bounce_reason = $request['event-data']['reason'];
                            $message->bounce_type = 'Permanent';
                            $message->save();
                        } else {
                            return response()->json(['type' => 'Permanent Failure', "message" => 'sent email not found'], 404);
                        }
                        break;

                    case 'temporary':
                        return response()->json(['type' => 'Temporary Failure']);
                        break;

                    default:
                        return response()->json(['type' => $request['event-data']['severity']]);
                        break;
                }
                break;

            default:
                break;
        }
    }
}
