<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Notification
{
    //
    public $timestamps = false;

    public $primaryKey = "notification_id";
    
    protected $fillable = [
        'notification_id',
        'comment_id'
    ];

    public function notification() {
        return $this->belongsTo(Notification::class);
    }

    public function comment() {
        return $this->belongsTo(Comment::class);
    }
}
