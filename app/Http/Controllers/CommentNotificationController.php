<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentNotification;

class CommentNotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Comment $comment)
    {
        $notification = NotificationController::store($comment->user_id);
        CommentNotification::create(['notification_id' => $notification->id, 'comment_id' => $comment->id]);
        return response()->json(['success' => true], 200);
    }
}
