<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;

    protected $fillable = ['content', 'user_id', 'news_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function news() {
        return $this->belongsTo(News::class);
    }

    public function commentVotes() {
        return $this->hasMany(CommentVote::class);
    }
}
