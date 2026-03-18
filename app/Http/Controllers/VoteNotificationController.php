<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\VoteNotification;
use App\Models\NewsVote;
use App\Models\CommentVote;
use App\Models\News;
use App\Models\Comment;

class VoteNotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Vote $vote)
    {
        // Chekc if the vote is on news or comment
        $targetId = null;

        $newsVote = NewsVote::find($vote->id);
        if (!is_null($newsVote)) {
            $news = News::find($newsVote->news_id);
            if (!is_null($news)) 
                $targetId = $news->user_id;
        }
        else {
            $commentVote = CommentVote::find($vote->id);
            if (!is_null($commentVote)) {
                $comment = Comment::find($commentVote->comment_id);
                if (!is_null($comment)) 
                    $targetId = $comment->user_id;
            }
        }

        if (is_null($targetId)) {
            return response()->json(['success' => false, 'error' => 'No proper notification target'], 400);
        }

        $notification = NotificationController::store($targetId);
        VoteNotification::create(['notification_id' => $notification->id, 'vote_id' => $vote->id]);

        return response()->json(['success' => true], 200);
    }
}
