@extends('layouts.app')

@php
    use App\Models\Admin;
    use App\Models\Moderator;
@endphp

@section('title', $news->title . ' | ' . config('app.name'))

@push('scripts')
    <script src="{{ asset('js/comments.js') }}" defer></script>
    <script src="{{ asset('js/votes.js') }}" defer></script>
    <script src="{{ asset('js/moderator.js') }}" defer></script>
@endpush

@section('maincontent')
    <section id="news">
        <article class="news" data-id="{{ $news->id }}">
            <div class="news-component">
                <div class="news-header">
                    <div class="news-user-info">
                        <img src="{{ $news->user->getProfileImage() }}" alt="User Profile" class="news-user-pic">
                        <a href="{{ route('user.show', ['user' => $news->user]) }}" class="news-username-link">
                            <p class="news-username">{{ $news->user->username }}</p>
                        </a>
                    </div>
                    <div class="news-title-section">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <h2 class="news-title">
                                <a href="{{ route('news.show', ['news' => $news]) }}" style="text-decoration: none; color: inherit;">
                                    {{ $news->title }}
                                </a>
                            </h2>
                            @auth
                                @if(Moderator::where('user_id', Auth::id())->exists() && $news->user_id !== Auth::id())
                                    <button class="checkmark-btn {{ $news->hasCheckmark() ? 'active' : '' }}" onclick="toggleCheckmark({{ $news->id }})">
                                        <span id="checkmark-icon-{{ $news->id }}">✓</span>
                                    </button>
                                @elseif($news->hasCheckmark())
                                    <div class="checkmark-btn active" style="cursor: default;">
                                        <span>✓</span>
                                    </div>
                                @endif
                            @else
                                @if($news->hasCheckmark())
                                    <div class="checkmark-btn active" style="cursor: default;">
                                        <span>✓</span>
                                    </div>
                                @endif
                            @endauth
                        </div>
                        <div class="news-categories">
                            @foreach($news->categories as $category)
                                <a href="{{ route('search.category', ['category' => $category->name]) }}" class="category-tag-link">
                                    <span class="category-tag">{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="news-content">
                    <img src="{{ $news->getImage() }}" alt="News Image" class="news-image">
                    <p class="news-description">{{ $news->content }}</p>
                </div>
                
                <div class="news-actions">
                    @auth
                        @php
                            $userVote = $news->newsVotes()
                                ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                                ->where('news_votes.user_id', Auth::id())
                                ->value('votes.value');
                            $upvotes = $news->newsVotes()
                                ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                                ->where('votes.value', 1)
                                ->count();
                            $downvotes = $news->newsVotes()
                                ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                                ->where('votes.value', -1)
                                ->count();
                        @endphp
                        <div class="vote-buttons">
                            <button class="vote-btn upvote {{ $userVote == 1 ? 'active' : '' }}" onclick="vote({{ $news->id }}, 1)">
                                <img src="{{ asset($userVote == 1 ? 'images/icones/gostar1.png' : 'images/icones/gostar.png') }}" alt="Like" class="vote-icon">
                            </button>
                            <span class="vote-count" id="upvote-count-{{ $news->id }}">{{ $upvotes }}</span>
                            <button class="vote-btn downvote {{ $userVote == -1 ? 'active' : '' }}" onclick="vote({{ $news->id }}, -1)">
                                <img src="{{ asset($userVote == -1 ? 'images/icones/nao-gosto1.png' : 'images/icones/nao-gosto.png') }}" alt="Dislike" class="vote-icon">
                            </button>
                            <span class="vote-count" id="downvote-count-{{ $news->id }}">{{ $downvotes }}</span>
                        </div>
                    @else
                        <div class="vote-buttons">
                            <button class="vote-btn-guest"><img src="{{ asset('images/icones/gostar.png') }}" alt="Like" class="vote-icon"></button>
                            <span class="vote-count">{{ $news->newsVotes()->join('votes', 'news_votes.vote_id', '=', 'votes.id')->where('votes.value', 1)->count() }}</span>
                            <button class="vote-btn-guest"><img src="{{ asset('images/icones/nao-gosto.png') }}" alt="Dislike" class="vote-icon"></button>
                            <span class="vote-count">{{ $news->newsVotes()->join('votes', 'news_votes.vote_id', '=', 'votes.id')->where('votes.value', -1)->count() }}</span>
                        </div>
                    @endauth
                    <span class="news-comments-count">🗩 {{ $news->comments()->count() }}</span>
                </div>

                @auth
                    @if(Auth::user()->id === $news->user_id || Auth::user()->isAdmin())
                        <div class="news-actions">
                            <a href="{{ route('news.edit', ['news' => $news]) }}" class="action-text-link">
                                Edit News
                                <img src="{{ asset('images/icones/editar-texto.png') }}" alt="Edit" class="action-icon">
                            </a>
                            @if($news->newsVotes()->count() == 0 && $news->comments()->count() == 0)
                                <a href="{{ route('news.delete',  ['news' => $news]) }}" class="action-text-link">
                                    Delete News
                                    <img src="{{ asset('images/icones/excluir.png') }}" alt="Delete" class="action-icon">
                                </a>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('report.create', [
                                        'user' => $news->user->id,
                                        'target_url' => rawurlencode(url()->current())]) }}" 
                            class="btn btn-sm btn-outline-danger">Report</a>
                    @endif
                @endauth
                
                <div class="news-comments">
                    <h3>Comments</h3>
                    @forelse($news->comments()->orderBy('date', 'desc')->get() as $comment)
                        <div class="comment" id="comment-{{ $comment->id }}">
                            <div class="comment-container">
                                <div class="comment-content">
                                    <p style="margin: 0;"><strong><a href="{{ route('user.show', ['user' => $comment->user]) }}" class="fw-bold text-decoration-none text-dark">{{ $comment->user->username }}</a>:</strong> <span id="comment-text-{{ $comment->id }}">{{ $comment->content }}</span></p>
                                    <div class="comment-vote-section">
                                        @auth
                                            @php
                                                $commentUserVote = $comment->commentVotes()
                                                    ->join('votes', 'comment_votes.vote_id', '=', 'votes.id')
                                                    ->where('comment_votes.user_id', Auth::id())
                                                    ->value('votes.value');
                                                $commentUpvotes = $comment->commentVotes()
                                                    ->join('votes', 'comment_votes.vote_id', '=', 'votes.id')
                                                    ->where('votes.value', 1)
                                                    ->count();
                                                $commentDownvotes = $comment->commentVotes()
                                                    ->join('votes', 'comment_votes.vote_id', '=', 'votes.id')
                                                    ->where('votes.value', -1)
                                                    ->count();
                                            @endphp
                                            <div class="vote-buttons">
                                                <button class="vote-btn upvote {{ $commentUserVote == 1 ? 'active' : '' }}" onclick="voteComment({{ $comment->id }}, 1)">
                                                    <img src="{{ asset($commentUserVote == 1 ? 'images/icones/gostar1.png' : 'images/icones/gostar.png') }}" alt="Like" class="vote-icon">
                                                </button>
                                                <span class="vote-count" id="comment-upvote-count-{{ $comment->id }}">{{ $commentUpvotes }}</span>
                                                <button class="vote-btn downvote {{ $commentUserVote == -1 ? 'active' : '' }}" onclick="voteComment({{ $comment->id }}, -1)">
                                                    <img src="{{ asset($commentUserVote == -1 ? 'images/icones/nao-gosto1.png' : 'images/icones/nao-gosto.png') }}" alt="Dislike" class="vote-icon">
                                                </button>
                                                <span class="vote-count" id="comment-downvote-count-{{ $comment->id }}">{{ $commentDownvotes }}</span>
                                            </div>
                                        @else
                                            <div class="vote-buttons">
                                                <button class="vote-btn-guest"><img src="{{ asset('images/icones/gostar.png') }}" alt="Like" class="vote-icon"></button>
                                                <span class="vote-count">{{ $comment->commentVotes()->join('votes', 'comment_votes.vote_id', '=', 'votes.id')->where('votes.value', 1)->count() }}</span>
                                                <button class="vote-btn-guest"><img src="{{ asset('images/icones/nao-gosto.png') }}" alt="Dislike" class="vote-icon"></button>
                                                <span class="vote-count">{{ $comment->commentVotes()->join('votes', 'comment_votes.vote_id', '=', 'votes.id')->where('votes.value', -1)->count() }}</span>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                                @auth
                                    @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                        <div class="comment-actions-btns">
                                            <button onclick="editComment({{ $comment->id }})" class="comment-action-link">
                                                Edit
                                                <img src="{{ asset('images/icones/editar-texto.png') }}" alt="Edit" class="comment-action-icon">
                                            </button>
                                            @if($comment->commentVotes()->count() == 0)
                                                <button onclick="deleteComment({{ $comment->id }})" class="comment-action-link">
                                                    Delete
                                                    <img src="{{ asset('images/icones/excluir.png') }}" alt="Delete" class="comment-action-icon">
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <a href="{{ route('report.create', [
                                                    'user' => $comment->user->id,
                                                    'target_url' => rawurlencode(url()->current()  . '#comment-' . $comment->id)]) }}" 
                                            class="btn btn-sm btn-outline-danger">Report</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p>No comments yet.</p>
                    @endforelse
                    @auth
                        <div class="comment-input-section">
                            <input type="text" id="comment-input-{{ $news->id }}" placeholder="Write a comment..." class="comment-input">
                            <button onclick="submitComment({{ $news->id }})" class="comment-submit-btn">Send</button>
                        </div>
                    @else
                        <p style="text-align: center; color: #666; margin-top: 15px;">Login to comment</p>
                    @endauth
                </div>
            </div>
        </article>
    </section>
@endsection