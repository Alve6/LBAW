@extends('layouts.app')

@section('title',  'Register | ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3 text-center">
      <label for="username">Username (required):</label>
      <input
          id="username"
          type="text"
          name="username"
          value="{{ old('username') }}"
          required
          autofocus
          autocomplete="username"
      >
      @error('username')
        <span id="username-error" class="error" role="alert">{{ $message }}</span>
      @enderror
    </div>

    <div class="mb-3 text-center">
      <label for="name">Name (required):</label>
      <input
          id="name"
          type="text"
          name="name"
          value="{{ old('name') }}"
          required
          autofocus
          autocomplete="name"
      >
      @error('name')
        <span id="name-error" class="error" role="alert">{{ $message }}</span>
      @enderror
    </div>

    <div class="mb-3 text-center">
      <label for="email">E-Mail Address (required):</label>
      <input
          id="email"
          type="email"
          name="email"
          value="{{ old('email') }}"
          required
          autocomplete="email"
          inputmode="email"
      >
      @error('email')
        <span id="email-error" class="error" role="alert">{{ $message }}</span>
      @enderror
    </div>

    <div class="mb-3 text-center">
      <label for="password">Password (required):</label>
      <input
          id="password"
          type="password"
          name="password"
          required
          autocomplete="new-password"
      >
      @error('password')
        <span id="password-error" class="error" role="alert">{{ $message }}</span>
      @enderror
    </div>

    <div class="mb-3 text-center">
      <label for="password-confirm">Confirm Password (required):</label>
      <input
          id="password-confirm"
          type="password"
          name="password_confirmation"
          required
          autocomplete="new-password"
      >
    </div>

    <div class="mb-3 text-center">
      <button type="submit">Register</button>
    </div>

    <div class="mb-3 text-center">
        <a href="{{ route('login') }}">Already have an account? Login Now!</button>
    </div>
</form>
@endsection