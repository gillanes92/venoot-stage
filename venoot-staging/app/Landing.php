<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    protected $fillable = [
        'which', 'button_banner', 'highlight', 'second_title', 'has_description', 'description_title', 'where_title', 'when_title', 'hour_title', 'has_speakers', 'speakers_title', 'has_activities', 'activities_title', 'has_location', 'location_title', 'location_description', 'has_sponsors', 'sponsors_title', 'images_title', 'confirm_title', 'has_contact', 'contact_title', 'has_ssnn', 'ssnn_title', 'images', 'contact_subtitle', 'address_title', 'address', 'phone_title', 'phone', 'show_email', 'show_contact_form', 'tickets_title', 'tickets_subtitle', 'video_url', 'location_photo', 'has_date', 'has_add_to_calendar', 'show_sponsor_title',
        'confirm_subtitle', 'show_speaker_title', 'loop', 'show_tickets'
    ];

    protected $casts = [
        'images' => 'array',
        // 'loop' => 'boolean'
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
