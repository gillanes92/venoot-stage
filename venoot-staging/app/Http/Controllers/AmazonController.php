<?php

namespace App\Http\Controllers;

use App\SentEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AmazonController extends Controller
{
    public function handleBounceOrComplaint(Request $request)
    {
        $message = $request->json('event-data');

        switch ($message['severity']) {
            case 'permanent':
                $bounced_message = SentEmail::where('message_id', $message['message']['headers']['message-id'])->first();
                if ($bounced_message) {
                    $bounced_message->bounced = true;
                    $bounced_message->bounce_reason = $message['reason'];
                    $bounced_message->bounce_type = 'Permanent';
                    $bounced_message->save();

                    $participant = $bounced_message->participation->participant;
                    $participant->blocked = true;
                    $participant->save();
                } else {
                    return response()->json(["message" => 'sent email not found'], 404);
                }

                break;

                //case 'Complaint':
                // $complaint = $message['complaint'];
                // foreach ($complaint['complainedRecipients'] as $complainedRecipient) {
                //     $emailAddress = $complainedRecipient['emailAddress'];
                //     $emailRecord = WrongEmail::firstOrCreate(['email' => $emailAddress, 'problem_type' => 'Complaint']);
                //     if ($emailRecord) {
                //         $emailRecord->increment('repeated_attempts', 1);
                //     }
                // }
                //  break;
            default:
                // Do Nothing
                break;
        }
        return response()->json(['status' => 200, "message" => 'success']);
    }
}
