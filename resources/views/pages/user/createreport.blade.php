@extends('layouts.app')

@section('title',  'Report | ' . config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
    <form method="POST" action="{{ route('report.store') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <input type="hidden" name="target_url" value="{{ $target_url }}">
        <div class="mb-3 text-center">
            <label for="content">Content</label>
            <textarea
                id="content"
                name="content"
                rows="10"
                cols="50"
                maxlength="1000"
                placeholder="Describe the reason for reporting this content..."
            ></textarea>
            @error('content')
                <span id="content-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 text-center">
            <button type="submit">Submit Report</button>
            <a href="{{ rawurldecode($target_url) }}" class="button">Cancel</a>
        </div>
    </form>
@endsection