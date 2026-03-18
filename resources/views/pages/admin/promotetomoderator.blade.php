@extends('layouts.app')

@section('title',  $user->username . ' Mod Promotion | ' . config('app.name'))

@section('maincontent')
    <div class="mb-3 text-center">
        <h2>Promote {{ $user->username }} to Moderator</h2>
    </div>
        <form method="POST" action="{{ route('user.promoteToModerator', ['user' => $user]) }}">
            @csrf
            <div class="mb-3 text-center">
                <p>Are you sure you want to promote {{ $user->username }} to a Moderator? You can't reverse this action.</p>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="button mb-0">Yes</button>
            </div>
            <div class="mb-3 text-center">
                <a href="{{ route('user.show', $user) }}" class="button">Cancel</a>
            </div>
        </form>
    </div>
@endsection