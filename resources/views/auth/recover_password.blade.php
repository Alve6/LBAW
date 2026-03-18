@extends('layouts.app')

@section('title',  'Recovery | ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
<form method="POST" action="{{ route('recoverPassword', ['user' => $user]) }}">
    @csrf
    <p>An email was sent to the supplied email address with the recovery code, please check your inbox.</p>
    <div class="mb-3 text-center">
      <label for="password">Password</label>
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
      <label for="password-confirm">Confirm Password</label>
      <input
          id="password-confirm"
          type="password"
          name="password_confirmation"
          required
          autocomplete="new-password"
      >
    </div>

    <div class="mb-3 text-center">
      <label for="code">Recovery Code</label>
      <input
          id="code"
          type="text"
          name="code"
          required
      >
      @error('code')
        <span id="code-error" class="error" role="alert">{{ $message }}</span>
      @enderror
    </div>
    <div class="mb-3 text-center">
        <button type="submit">Recover Password</button>
    </div>
</form>
@endsection