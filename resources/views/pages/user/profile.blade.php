@extends('layouts.app')

@section('title',  $user->username . ' | ' . config('app.name'))

@php
    use App\Models\Moderator;
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush
    
@section('maincontent')
    <div class="profile_info_main">
        <div class="profile-top">
            <img src="{{ $user->getProfileImage() }}" alt="Profile Picture" class="profile-main-pic">
            <div class="profile-details">
                <h2 class="profile-username">{{ $user->username }}</h2>
                <p class="profile-name">{{ $user->name }}</p>
                <p class="profile-description">{{ $user->description ?? 'No description available.' }}</p>
                
                @auth
                    <div class="profile-actions">
                        @if(Auth::user()->id === $user->id && $user->username !== 'deleted_user_' . $user->id)
                            <a href="{{ route('user.edit', ['user' => $user]) }}" class="action-text-link">
                                Edit Profile
                                <img src="{{ asset('images/icones/editar-texto.png') }}" alt="Edit" class="action-icon">
                            </a>
                            <a href="{{ route('user.delete',  ['user' => $user]) }}" class="action-text-link">
                                Delete Account
                                <img src="{{ asset('images/icones/excluir.png') }}" alt="Delete" class="action-icon">
                            </a>
                        @elseif(Auth::user()->isAdmin() && $user->username !== 'deleted_user_' . $user->id && !$user->isAdmin())
                            <a href="{{ route('user.delete',  ['user' => $user]) }}" class="action-text-link">
                                Ban Account
                                <img src="{{ asset('images/icones/banir-usuario.png') }}" alt="Ban" class="action-icon">
                            </a>
                            @if(!$user->isModerator())
                                <a href="{{ route('user.showPromoteToModerator',  ['user' => $user]) }}" class="action-text-link">
                                    Promote to Moderator
                                </a>
                                <a href="{{ route('user.showPromoteToAdmin',  ['user' => $user]) }}" class="action-text-link">
                                    Promote to Admin
                                </a>
                            @endif
                        @endif
                        
                        @if(Auth::id() !== $user->id)
                            <button
                                id="follow-btn"
                                class="action-text-link"
                                data-user-id="{{ $user->id }}"
                                data-following="{{ $isFollowing ? '1' : '0' }}"
                                data-follow-url="{{ route('user.follow', $user->id) }}"
                                data-unfollow-url="{{ route('user.unfollow', $user->id) }}"
                            >
                                {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                                <img src="{{ asset('images/icones/seguir.png') }}" alt="Follow" class="action-icon">
                            </button>
                        @endif
                        
                        @if(Moderator::where('user_id', Auth::id())->exists() && Auth::id()!=$user->id)
                            @if(!$user->isTimedOut())
                                <a href="{{ route('user.timeout', ['user'=>$user])}}" class="action-text-link">
                                    Timeout
                                    <img src="{{ asset('images/icones/tempo-esgotado.png') }}" alt="Timeout" class="action-icon">
                                </a>
                            @endif
                        @endif
                        
                        @if($user->isTimedOut())
                            <span class="timeout-badge"><strong>Currently Timed Out</strong></span>
                        @endif
                    </div>
                @endauth

                <div class="profile-stats">
                    <span><strong>{{ $followersCount }}</strong> followers</span>
                    <span><strong>{{ $followingCount }}</strong> following</span>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-icon">📰</span>
                        <span class="stat-count">{{ $user->news()->count() }} News</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon">♡</span>
                        <span class="stat-count">{{ $user->reputation() }} Likes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon">🗩</span>
                        <span class="stat-count">{{ $user->comments()->count() }} Comments</span>
                    </div>
                </div>
            </div>
        </div>
        
        @if($user->news()->count() > 0)
            <div class="profile-news-section">
                <h2>Published News</h2>
                <div class="profile-news-list">
                    @foreach($user->news()->orderBy('date', 'desc')->take(5)->get() as $news)
                        <div class="profile-news-item">
                            <a href="{{ route('news.show', ['news' => $news]) }}">
                                <h3>{{ $news->title }}</h3>
                                <p>{{ Str::limit($news->content, 150) }}</p>
                                <span class="news-date">{{ \Carbon\Carbon::parse($news->date)->format('d M Y') }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>     
@endsection
