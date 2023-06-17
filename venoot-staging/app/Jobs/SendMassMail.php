<?php

namespace App\Jobs;

use App\Participant;
use App\Participation;
use App\Event;
use App\User;
use App\ScheduledDelivery;
use App\Mail\ParticipantTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Jobs\SendMail;
use DateTime;

class SendMassMail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $user;
  protected $event;
  protected $request;
  protected $participants;
  protected $category;
  protected $body;
  protected $date;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(User $user, Event $event, Request $request, $participants, $category, $body, DateTime $date = null)
  {
    $this->user = $user;
    $this->event = $event;
    $this->request = $request->all();
    $this->participants = $participants;
    $this->category = $category;
    $this->body = $body;
    $this->date = $date;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $scheduled_delivery = null;
    $sheduled_jobs = [];
    if ($this->date) {
      $scheduled_delivery = $this->event->scheduled_deliveries()->create([
        'from_name' => $this->request['fromName'],
        'subject' => $this->request['subject'],
        'category' => $this->category,
        'send_at' => $this->date,
      ]);
    }

    $scheduled_mails = 0;
    foreach ($this->participants as $participant) {

      if ($participant->participations->count() > 0) {
        $participation = $participant->participations[0];
      } else {
        $participation = Participation::create(['event_id' => $this->event->id, 'participant_id' => $participant->id, 'uuid' => (string) Str::uuid()]);
      }

      $isInvitee = false;
      if ($this->event->invitees > 0) {
        $isInvitee = $this->event->participations->pluck('invitees')->flatten()->contains($participant->data['email']);
      }

      $current_body = $this->changeDynamicBody($participant, $participation, $this->body);
      $converter = new CssToInlineStyles();
      $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));

      if ($participant->allow_mailing && !$participant->blocked && ($this->user->company->allow_mailings || ($this->user->company->allow_verified_mailings && $participant->verified))) {

        if ($scheduled_delivery) {
          SendMail::dispatch($participant->data['email'], new ParticipantTemplate($this->event, $participation, $this->request['fromName'], $this->request['subject'], $this->category, $content), $this->date, $scheduled_delivery);
        } else {
          SendMail::dispatch($participant->data['email'], new ParticipantTemplate($this->event, $participation, $this->request['fromName'], $this->request['subject'], $this->category, $content), $this->date);
        }

        $scheduled_mails++;
      }

      switch ($this->category) {
        case 'ConfirmationRequest':
          $participation->confirmed_sent_at = Carbon::now();
          break;
      }

      $participation->save();
    }

    if ($scheduled_delivery) {
      $scheduled_delivery->mail_count = $scheduled_mails;
      $scheduled_delivery->save();
    }
  }

  private function changeStaticBody(Event $event, $body)
  {
    $body = str_replace("{URL-LANDING}", url("events/{$event->id}/landing"), $body);
    $body = str_replace("{URL-CHAT}", url("venoot-chat"), $body);
    $body = str_replace("{TITULO}", $event->name, $body);
    $body = str_replace("{LOCACION}", $event->location, $body);

    return $body;
  }

  private function changeDynamicBody(Participant $participant, Participation $participation, $body, $template = null)
  {
    $body = str_replace("{NOMBRE}", $participant->data['name'], $body);
    $body = str_replace("{APELLIDO}", $participant->data['lastname'], $body);
    $body = str_replace("{ALOALA}", $participant->data['vocativo'], $body);

    $body = str_replace("{QR-NUMERO}", $participation->uuid, $body);
    $body = str_replace("{QR-EVENTO}", url("qr/{$participation->uuid}"), $body);
    $body = str_replace("{QR-CODE}", base64_encode(QrCode::format('png')->size(300)->generate($participation->uuid)), $body);
    $body = str_replace("{CONFIRMAR-SI}", url("confirms/{$participation->uuid}"), $body);
    $body = str_replace("{CONFIRMAR-NO}", url("unconfirms/{$participation->uuid}"), $body);
    $body = str_replace("{INVITAR}", url("invitees/{$participation->uuid}"), $body);

    if ($template) {
      $body = str_replace("{PDF}", url("templates/{$template->id}/{$participation->uuid}"), $body);
    }

    return $body;
  }
}
