<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request, User $user)
    {
        $auth = $request->user();

        // If somehow the request is made to follow yourself (cannot happen via UI),
        // silently ignore. The ideia is to not revel the button of follow when you are viewing your own profile as a safety measure.
        
        if ($auth->id === $user->id) {
            return response()->json(['status' => 'noop']);
        }

        if (! $auth->isFollowing($user)) {
            $auth->following()->attach($user->id);
        }

        return response()->json([
            'status'          => 'followed',
            'followers_count' => $user->followers()->count(),
        ]);
    }

    public function unfollow(Request $request, User $user)
    {
        $auth = $request->user();

        if ($auth->id === $user->id) {
            return response()->json(['status' => 'noop']);
        }

        $auth->following()->detach($user->id);

        return response()->json([
            'status'          => 'unfollowed',
            'followers_count' => $user->followers()->count(),
        ]);
    }
}
