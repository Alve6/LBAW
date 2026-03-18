@extends('layouts.app')

@section('title',  'Login | ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
<form method="POST" action="{{ route('login') }}">
    @csrf
    @error('timeout')
        <div class="mb-3 text-center">
            <span class="error" role="alert">
                {{ $message }}
            </span>
        </div>
    @enderror
    <div class="mb-3 text-center">
        <label for="username">Username (required): </label>
        <input
            id="username"
            name="username"
            type="text"
            value="{{ old('username') }}"
            required
            autofocus
        >
        @error('username')
            <span id="username-error" class="error" role="alert">
                {{ $message }}
            </span>
        @enderror
    </div>
    <div class="mb-3 text-center">
        <label for="password" >Password (required): </label>
        <input
            id="password"
            name="password"
            type="password"
            required
            autocomplete="current-password"
        >
        @error('password')
            <span id="password-error" class="error" role="alert">
                {{ $message }}
            </span>
        @enderror
    </div>
    <!--
        <label>
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            Remember me
        </label>
    -->
    <div class="mb-3 text-center">
        <button type="submit">Login</button>
    </div>
    <div class="mb-3 text-center">
        <a href="{{ route('register') }}">Don't have an account? Register Now!</a>
    </div>
    <div class="mb-3 text-center">
        @if (session('status'))
            <p class="success" role="status">{{ session('status') }}</p>
        @endif
    <div class="mb-3 text-center">
        <a href="{{ route('showRecoveryForm') }}">Recover Password.</a>
    </div>
</form>
@endsection