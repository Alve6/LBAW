@extends('layouts.app')

@section('title', 'Apply Timeout - ' . $user->username . ' | ' . config('app.name'))

@push('scripts')
    <script src="{{ asset('js/moderator.js') }}" defer></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">
@endpush

@section('maincontent')    
    <section id="timeout-section" class="timeout-container">
        <div class="news-component">
            <h2 class="timeout-title">Apply Timeout</h2>
            
            <div class="timeout-user-card">
                <img src="{{ $user->getProfileImage() }}" alt="User Profile" class="news-user-pic">
                <div class="timeout-user-details">
                    <p class="news-username">{{ $user->username }}</p>
                    <p class="timeout-user-name">{{ $user->name }}</p>
                </div>
            </div>
            
            <div class="timeout-form">
                <div class="timeout-field">
                    <label for="timeout-duration" class="timeout-label">
                        Timeout Duration (required):
                    </label>
                    <input 
                        type="number" 
                        id="timeout-duration" 
                        class="comment-input" 
                        placeholder="Enter duration in hours..."
                        min="1"
                        max="168"
                        required
                    >
                </div>
                
                <div class="timeout-field">
                    <label for="timeout-reason" class="timeout-label">
                        Reason (required)::
                    </label>
                    <textarea 
                        id="timeout-reason" 
                        class="comment-input tiremeout-textarea" 
                        placeholder="Enter the reason for applying timeout..."
                        rows="4"
                        maxlength="500"
                        required
                    ></textarea>
                </div>
                
                <div class="timeout-actions">
                    <button 
                        class="comment-submit-btn timeout-submit-btn"
                        onclick="submitTimeout({{ $user->id }})"
                    >
                        Submit
                    </button>
                    <a href="{{ route('user.show', ['user' => $user]) }}">
                        <button class="comment-action-btn timeout-cancel-btn">
                            Cancel
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection