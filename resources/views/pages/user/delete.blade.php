@extends('layouts.app')

@section('title', $user->username . ' | ' . config('app.name'))

@section('maincontent')
    <form method="POST" action="{{ route('user.destroy', $user) }}">
        @csrf
        @method('DELETE')
        @if ($user->id === Auth::user()->id)        
            <div class="mb-3 text-center">
                <p> 
                    Hey!<br>
                    We are sorry to see you leaving...<br>
                    If you decide to delete your account, keep in mind that this process is irreversible. <br>
                    Your posts and comments won’t disappear from this platform, but they’ll be completely anonymised so no one can trace them back to you.<br>
                    Are you absolutely sure you want to continue with the deletion?<br>
                </p>
            </div>
        @elseif (Auth::user()->isAdmin())
            <div class="mb-3">
                <p> 
                    Hey!<br>
                    As an admin, you have the important task of keeping our comunity safe. <br>
                    We trust your judgement that this user breaks, frequently, the rules of the website, but we still want to inform you that:
                    <ol>
                        <li>We will keep a log of your action for which we reserve the right to check in case of need.</li>
                        <li>This action can't be reversed.</li>
                        <li>This action should only be taken in case the user frequently breaks the rules.</li>
                    </ol>
                </p>
            </div>
        @endif

        <div class="mb-3 text-center">
            <input id="confirmation" type="checkbox" required>
            <label for="confirmation">I have read the message above, understand the consequencies of this process and whish to proceed.</label>
        </div>

        <div class="mb-3 text-center">
            <button type="submit">Delete</button>
        </div>

        <div class="mb-3 text-center">
            <a href="{{ route('user.show', $user) }}">Cancel</a>
        </div>
    </form>
@endsection