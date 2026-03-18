@extends('layouts.app')

@php
    use App\Models\Admin;
@endphp

@section('title', 'Delete ' . $news->title . '|' . config('app.name'))

@section('maincontent')
    <form method="POST" action="{{ route('news.destroy', $news) }}">
        @csrf
        @method('DELETE')
        @if($news->user_id === Auth::user()->id)
            <div class="mb-3 text-center">
                <p> 
                    Hey!<br>
                    We see that you want to delete this news piece. A few warnings bellow:<br>
                    If you decide to delete this piece, keep in mind that it is irreversible. <br>
                    If this piece has been commented or voted on, you won't be able to delete it.<br>
                    Are you absolutely sure you want to continue with the deletion?<br>
                </p>
            </div>
        @elseif (Auth::user()->isAdmin())
            <div class="mb-3">
                <p> 
                    Hey!<br>
                    As an admin, you have the important task of keeping our comunity safe. <br>
                    We trust your judgement that this news piece breaks the rules of the website, but we still want to inform you that:
                    <ol>
                        <li>We will keep a log of your action for which we reserve the right to check in case of need.</li>
                        <li>This action can't be reversed.</li>
                        <li>The user will be notified of this deletion.</li>
                    </ol>
                </p>
            </div>
        @endif

        <div class="mb-3 text-center">
            <input id="confirmation" type="checkbox" required>
            <label for="confirmation">I have read the message above, understand the consequencies of this process and whish to proceed.</label>
            @error('news')
                <span id="news-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <button type="submit">Delete</button>
        </div>

        <div class="mb-3 text-center">
            <a href="{{ route('news.show', $news) }}">Cancel</a>
        </div>
    </form>
@endsection