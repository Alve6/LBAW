@extends('layouts.app')

@section('title', $user->username . ' | ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
    <form method="POST" action="{{ route('user.update', $user) }}" enctype="multipart/form-data">

        @csrf
        @method('PATCH')
        <div class="mb-3 text-center">
            <label for="username">Username</label>
            <input
                id="username"
                type="text"
                name="username"
                value="{{ old('username') }}"
                autofocus
                autocomplete="username"
                placeholder="{{ $user->username }}"
            >
            @error('username')
            <span id="username-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <label for="name">Name</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                autofocus
                autocomplete="name"
                placeholder="{{ $user->name }}"
            >
            @error('name')
            <span id="name-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <label for="email">E-Mail Address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                inputmode="email"
                placeholder="{{ $user->email }}"
            >
            @error('email')
            <span id="email-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <label for="file">Image</label>
            <input name="file" type="file">
            <input name="type" type="text" value="users" hidden>
            @error('file')
                <span id="file-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center"> 
            <label for="current_password">Current Password (required):</label>
            <input
                id="current_password"
                type="password"
                name="current_password"
                required
            >
            @error('current_password')
            <span id="current_password-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <label for="new_password">New Password</label>
            <input
                id="new_password"
                type="password"
                name="new_password"
                autocomplete="new-password"
            >
            @error('new_password')
            <span id="new_password-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <label for="new_password-confirm">Confirm Password</label>
            <input
                id="new_password-confirm"
                type="password"
                name="new_password_confirmation"
                autocomplete="new-password"
            >
        </div>

        <div class="mb-3 text-center">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="10"
                cols="50"
                maxlength="1000"
                placeholder="Tell us more about you in here :) !!! Stay within 1000 characters."
            >{{ $user->description ?? "" }}</textarea>
        </div>

        <div class="mb-3 text-center">
            <button type="submit">Edit</button>
            <a href="{{ route('user.show', $user) }}" class="button">Cancel</a>
        </div>
    </form>
@endsection