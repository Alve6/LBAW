<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public static function store(int $user_id)
    {
        $notification = Notification::create(['user_id' => $user_id]);
        return $notification;
    }

    /**
     * Display the specified resource.
     */
    public function index(User $user)
    {
        if(Auth::user()->id !== $user->id) {
            abort(403);
        }
        $notifications = Notification::where('user_id', $user->id)->orderBy('date', 'desc')->get();
        return view('pages.user.notifications', ['notifications' => $notifications]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function markSeen(Notification $notification)
    {
        Gate::authorize('update', $notification);
        $notification->update(['seen' => True]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        Gate::authorize('delete', $notification);
        $notification->delete();
        return redirect()->back();
    }
}
