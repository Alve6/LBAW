@extends('layouts.app')

@section('title', 'Notifications | ' . config('app.name'))

@section('maincontent')
    <ul class="notifications-list">
        <div class="list-group">
            @if($notifications->isEmpty())
                <p class="text-center">You have no notifications.</p>
            @else
                @foreach($notifications as $notification)
                    <div class="mb-2">
                        @include('partials.notification', ['notification' => $notification])
                    </div>
                @endforeach
            @endif
        </div>
    </ul>
@endsection