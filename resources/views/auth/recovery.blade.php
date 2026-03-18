@extends('layouts.app')

@section('title',  'Recovery | ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
<form method="POST" action="{{ route('send') }}">
    @csrf
    <div class="mb-3 text-center">
        <label for="email">Your email (required):</label>
        <input id="email" type="email" name="email" placeholder="Email" required>
        @error('email')
            <span id="email-error" class="error" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3 text-center">
        <button type="submit">Send Recovery Code</button>
    </div>
</form>
@endsection