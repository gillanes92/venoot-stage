<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledDelivery extends Model
{
    protected $fillable = [
        'from_name', 'subject', 'category', 'send_at', 'mail_count', 'jobs',
    ];

    protected $casts = [
        'jobs' => 'array',
    ];

    protected $hidden = [
        'jobs'
    ];

    protected $appends = ['unprocessed', 'done', 'running'];

    public function getUnprocessedAttribute()
    {
        return count($this->jobs);
    }

    public function getDoneAttribute()
    {
        return $this->unprocessed > 0;
    }

    public function getRunningAttribute()
    {
        return $this->unprocessed < $this->mail_count;
    }

    /**
     * Get the event that owns the ScheduledDelivery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
