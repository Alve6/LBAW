<?php

namespace App\Models;

class VoteNotification extends Notification
{
    //

    public $timestamps = false;
    public $primaryKey = "notification_id";

    protected $fillable = [
        'notification_id',
        'vote_id'
    ];

    public function notification() {
        return $this->belongsTo(Notification::class);
    }

    public function vote() {
        return $this->belongsTo(Vote::class);
    }
}
