<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NewsPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): bool
    {
        return $user->id === $news->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        return $user->id === $news->user_id || $user->isAdmin();
    }
}
