<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsVote extends Vote
{
    //
    public $timestamps = false;
    public $primaryKey = 'vote_id';
    protected $fillable = ['vote_id', 'news_id', 'user_id'];

    public function vote() {
        return $this->belongsTo(Vote::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function news() {
        return $this->belongsTo(News::class);
    }
}
