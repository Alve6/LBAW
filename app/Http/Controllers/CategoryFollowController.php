<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryFollowController extends Controller
{
    public function follow(Request $request, Category $category)
    {
        $auth = $request->user();

        if (! $auth->isFollowingCategory($category)) {
            $auth->followedCategories()->attach($category->name);
        }

        return response()->json([
            'status' => 'followed',
            'followers_count' => $category->followers()->count(),
        ]);
    }

    public function unfollow(Request $request, Category $category)
    {
        $auth = $request->user();

        $auth->followedCategories()->detach($category->name);

        return response()->json([
            'status' => 'unfollowed',
            'followers_count' => $category->followers()->count(),
        ]);
    }
}
