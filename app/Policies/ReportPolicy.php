<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    public function create(User $user): bool
    {
        return Auth::check();
    }

    public function view(User $user): bool
    {
        return $user->isAdmin();
    }

    public function acknowledge(User $user): bool
    {
        return $user->isAdmin();
    }
}
