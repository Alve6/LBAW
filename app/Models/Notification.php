<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //

    public $timestamps = false;

    protected $fillable = [
        'seen',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function commentNotification()
    {
        return $this->hasOne(CommentNotification::class, 'notification_id', 'id');
    }

    public function voteNotification()
    {
        return $this->hasOne(VoteNotification::class, 'notification_id', 'id');
    }

    public function notifiable()
    {
        if (!is_null($this->commentNotification)) {
            return $this->commentNotification->comment;
        }
        else if (!is_null($this->voteNotification)) {
            return $this->voteNotification->vote;
        }

        return null;
    }

    public function url()
    {
        $target = $this->notifiable();
        if (is_null($target)) {
            return route('allNews');
        }
        else if ($target instanceof Comment) {
            return route('news.show', ['news' => $target->news_id]) . '#comment-' . $target->id;
        }
        else if (!is_null(NewsVote::find($target->id))) {
            return route('news.show', ['news' => NewsVote::find($target->id)->news_id]);
        }
        else if(!is_null(CommentVote::find($target->id))) {
            return route('news.show', ['news' => CommentVote::find($target->id)->comment->news_id]) . '#comment-' . CommentVote::find($target->id)->comment_id;
        }

        return route('allNews');
    }

    public function message()
    {
        $target = $this->notifiable();

        if (is_null($target)) {
            return 'New notification';
        }
        if ($target instanceof Comment) {
            return 'New comment on "' . $target->news->title . '"';
        }
        else if (!is_null(NewsVote::find($target->id))) {
            return 'Someone voted on your news';
        }
        else if(!is_null(CommentVote::find($target->id))) {
            return 'Someone voted on your comment';
        }
        
        return 'New notification';
    }
}
