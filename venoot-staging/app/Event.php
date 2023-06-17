<?php

namespace App;

use App\Job;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\AppDataResource;

class Event extends Model
{
    protected $fillable = [
        'category', 'banner', 'logo_event', 'name', 'location', 'start_date', 'start_time', 'end_date', 'end_time', 'description', 'profile_id', 'company_id', 'invitees', 'email', 'quota', 'over_quota', 'apology', 'extra_images', 'has_confirmation', 'confirmation_key', 'has_date', 'has_add_to_calendar', 'producer', 'producer_password', 'secure_video', 'secure_video_extras', 'shared_chat', 'video_category', 'confirmation_id', 'reconfirmation_id', 'qrcode_id', 'free_id', 'pretty_url', 'webpay_accepted', 'paypal_accepted', 'from_name', 'from_email', 'from_subject', 'one_to_one_chat', 'speaker_chat', 'personalize_tickets', 'is_webinar', 'host_webinar_link', 'presenter_webinar_link', 'timezone',
    ];

    protected $hidden = [
        'producer_password', 'localization'
    ];

    protected $with = [
        'activities',
        'sponsors',
        'landing',
        'estimate',
        'profile',
        'tickets',
        'actors',
        'collectors',
        'registerDevices'
    ];

    protected $casts = [
        'has_confirmation' => 'boolean',
        'landing.images' => 'array',
        'extra_images' => 'array',
        'localization' => 'object',
        'languages' => 'object'
    ];

    protected $appends = [
        'sent_mail_count', 'view_mail_count', 'bounced_mail_count', 'status', 'twitter_shares', 'facebook_shares', 'linkedin_shares', 'devices', 'referers',
    ];

    public function canEmail($amount = 1)
    {
        $event = $this;
        $email_count = $event->participations()
            ->with('sent_mails')
            ->get()
            ->flatMap(function ($participation) {
                return $participation->sent_mails;
            })
            ->count();

        return $this->estimate->mailings_quantity - $email_count >= $amount;
    }

    public function getLanguagesAttribute()
    {
        $localization = null;

        if ($this->localization) {
            $localization = [];
            foreach ($this->localization as $locale => $event_id) {
                if (!$event_id) {
                    $localization[$locale] = null;
                } else {
                    $localization[$locale] = new AppDataResource(Event::find($event_id)->participations()->first());
                }
            }
        }

        return $localization;
    }

    public function getProfiledParticipationsAttribute()
    {
        $participant_ids = $this->profile->participantsCollection->pluck('id');
        return $this->participations()->whereIn('participant_id', $participant_ids);
    }

    public function getRegisteredParticipantsCountAttribute()
    {
        $webinar = $this;
        return $this->participants()
            ->whereHas('participations', function ($query) use ($webinar) {
                $query->where('event_id', $webinar->id)->whereNotNull('registered_at');
            })
            ->count();
    }

    public function getSentMailCountAttribute()
    {
        return $this->sentMails()->count();
    }

    public function getViewMailCountAttribute()
    {
        return $this->sentMails()->where('opens', '>', 0)->get()->unique('recipient')->count();
    }

    public function getBouncedMailCountAttribute()
    {
        return $this->sentMails()->where('bounced', true)->count();
    }

    public function getStatusAttribute()
    {
        if (!$this->estimate) return 'undetermined';

        if ($this->estimate->status) {
            if ($this->published) {
                return 'published';
            } else {
                return 'pending';
            }
        } else {
            return 'editing';
        }
    }

    public function getTwitterSharesAttribute()
    {
        return $this->event_shares()->where('service', 'twitter')->count();
    }

    public function getFacebookSharesAttribute()
    {
        return $this->event_shares()->where('service', 'facebook')->count();
    }

    public function getLinkedinSharesAttribute()
    {
        return $this->event_shares()->where('service', 'linkedin')->count();
    }

    public function getReferersAttribute()
    {
        return $this->landing_visits()->pluck('service')->countBy();
    }

    public function getDevicesAttribute()
    {
        return $this->landing_visits()->pluck('device')->countBy();
    }

    // $item->start_time = Carbon::parse($item->start_time)->format('H:i');
    //             $item->end_time = Carbon::parse($item->end_time)->format('H:i');

    // public function getStartTimeAttribute()
    // {
    //     return Carbon::parse($this->attributes['start_time'])->format('H:i');
    // }

    // public function getEndTimeAttribute()
    // {
    //     return Carbon::parse($this->attributes['end_time'])->format('H:i');
    // }

    public function setOverQuotaAttribute($value)
    {
        $this->attributes['over_quota'] = is_null($value) ? false : $value;
    }

    public function setExtraImagesAttribute($value)
    {
        $this->attributes['extra_images'] = is_null($value) ? '[]' : json_encode($value);
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity')->orderBy('date')->orderBy('start_time');
    }

    public function sponsors()
    {
        return $this->belongsToMany('App\Sponsor')->withPivot('id')->orderBy('pivot_id');
    }

    public function estimate()
    {
        return $this->belongsTo('App\Estimate')->without('event');
    }

    public function polls()
    {
        return $this->belongsToMany('App\Poll')->withPivot(['created_at'])->withTimestamps();
    }

    public function landing()
    {
        return $this->hasOne('App\Landing');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function participants()
    {
        return $this->belongsToMany('App\Participant', 'participations');
    }

    public function sentMails()
    {
        return $this->hasMany('App\SentEmail');
    }

    public function sent_emails()
    {
        return $this->hasMany('App\SentEmail');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    public function participant_orders()
    {
        return $this->hasMany('App\ParticipantOrder');
    }

    public function paid_participant_orders()
    {
        return $this->hasMany('App\ParticipantOrder')->where('status', 1);
    }

    public function event_shares()
    {
        return $this->hasMany('App\EventShare');
    }

    public function landing_visits()
    {
        return $this->hasMany('App\LandingVisit');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Actor')->withPivot('id')->orderBy('pivot_id');
    }

    public function collectors()
    {
        return $this->belongsToMany('App\User');
    }

    public function registerDevices()
    {
        return $this->hasMany('App\Device');
    }

    public function app()
    {
        return $this->hasOne('App\App');
    }

    public function push_notifications()
    {
        return $this->hasMany('App\PushNotification');
    }

    public function chat_messages()
    {
        return $this->hasMany('App\ChatMessage');
    }

    public function queue_prints()
    {
        return $this->hasMany('App\QueuePrint');
    }

    public function getJobsAttribute()
    {
        return Job::all();
    }

    public function app_questions()
    {
        return $this->hasManyThrough('App\AppQuestion', 'App\Activity');
    }

    public function meetings()
    {
        return $this->hasMany('App\Meeting');
    }

    public function secure_video_views()
    {
        return $this->hasMany('App\SecureVideoView');
    }

    public function chat_participants()
    {
        return $this->hasMany('App\ChatParticipant');
    }

    public function stream_chat_messages()
    {
        return $this->hasMany('App\StreamChatMessage');
    }

    public function question_requests()
    {
        return $this->hasMany('App\QuestionRequest');
    }

    public function question_votes()
    {
        return $this->hasManyThrough('App\ParticipantQuestion', 'App\QuestionRequest');
    }

    public function submitted_questions()
    {
        return $this->hasManyThrough('App\ParticipantQuestion', 'App\Participation');
    }

    public function stream_questions()
    {
        return $this->belongsToMany('App\Question');
    }

    public function chat_activity_times()
    {
        return $this->hasManyThrough('App\ChatActivityTime', 'App\Participation');
    }

    public function confirmation_template()
    {
        return $this->belongsTo('App\Template', 'confirmation_id', 'id');
    }

    public function reconfirmation_template()
    {
        return $this->belongsTo('App\Template', 'reconfirmation_id', 'id');
    }

    public function qrcode_template()
    {
        return $this->belongsTo('App\Template', 'qrcode_id', 'id');
    }

    public function free_template()
    {
        return $this->belongsTo('App\Template', 'free_id', 'id');
    }

    /**
     * Get all of the scheduled_deliveries fent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduled_deliveries()
    {
        return $this->hasMany('App\ScheduledDelivery');
    }

    public function stands()
    {
        return $this->hasMany('App\Stand');
    }

    public function managers()
    {
        return $this->hasManyThrough('App\StandManager', 'App\Stand');
    }

    public function reminders()
    {
        return $this->hasMany('App\Reminder');
    }

    public function chatters()
    {
        return $this->hasMany('App\Chatter');
    }

    public function join_requests()
    {
        return $this->hasManyThrough('App\WebinarJoinRequest', 'App\Chatter');
    }

    public function sendings()
    {
        return $this->hasMany('App\Sending');
    }
}
