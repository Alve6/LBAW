@php
    use App\Models\Moderator;
@endphp

<article class="news" data-id="{{ $news->id }}">
    <div class="news-component">
        <div class="news-header">
            <div class="news-user-info">
                <img src="{{ $item['news']->user->getProfileImage() }}" alt="User Profile" class="news-user-pic">
                <a href="{{ route('user.show', ['user' => $item['news']->user]) }}" class="news-username-link">
                    <p class="news-username">{{ $item['news']->user->username }}</p>
                </a>
            </div>
            <div class="news-title-section">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <h2 class="news-title">
                        <a href="{{ route('news.show', ['news' => $item['news']]) }}" style="text-decoration: none; color: inherit;">
                            {{ $item['news']->title }}
                        </a>
                    </h2>
                    @auth
                        @if(Moderator::where('user_id', Auth::id())->exists() && $item['news']->user_id !== Auth::id())
                            <button class="checkmark-btn {{ $item['news']->hasCheckmark() ? 'active' : '' }}" onclick="toggleCheckmark({{ $item['news']->id }})">
                                <span id="checkmark-icon-{{ $item['news']->id }}">✓</span>
                            </button>
                        @elseif($item['news']->hasCheckmark())
                            <div class="checkmark-btn active" style="cursor: default;">
                                <span>✓</span>
                            </div>
                        @endif
                    @else
                        @if($item['news']->hasCheckmark())
                            <div class="checkmark-btn active" style="cursor: default;">
                                <span>✓</span>
                            </div>
                        @endif
                    @endauth
                </div>
                    </a>
                </h2>
                <div class="news-categories">
                    @foreach($item['categories'] as $category)
                        <a href="{{ route('search.category', ['category' => $category->name]) }}" class="category-tag-link">
                            <span class="category-tag">{{ $category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="news-content">
            <img src="{{ $item['news']->getImage() }}" alt="News Image" class="news-image">
            <p class="news-description">{{ Str::limit($item['news']->content, 200) }}</p>
        </div>
        
        <div class="news-actions">
            @auth
                @php
                    $userVote = $item['news']->newsVotes()
                        ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                        ->where('news_votes.user_id', Auth::id())
                        ->value('votes.value');
                    $upvotes = $item['news']->newsVotes()
                        ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                        ->where('votes.value', 1)
                        ->count();
                    $downvotes = $item['news']->newsVotes()
                        ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                        ->where('votes.value', -1)
                        ->count();
                @endphp
                <div class="vote-buttons">
                    <button class="vote-btn upvote {{ $userVote == 1 ? 'active' : '' }}" onclick="vote({{ $item['news']->id }}, 1)">
                        <img src="{{ asset($userVote == 1 ? 'images/icones/gostar1.png' : 'images/icones/gostar.png') }}" alt="Like" class="vote-icon">
                    </button>
                    <span class="vote-count" id="upvote-count-{{ $item['news']->id }}">{{ $upvotes }}</span>
                    <button class="vote-btn downvote {{ $userVote == -1 ? 'active' : '' }}" onclick="vote({{ $item['news']->id }}, -1)">
                        <img src="{{ asset($userVote == -1 ? 'images/icones/nao-gosto1.png' : 'images/icones/nao-gosto.png') }}" alt="Dislike" class="vote-icon">
                    </button>
                    <span class="vote-count" id="downvote-count-{{ $item['news']->id }}">{{ $downvotes }}</span>
                </div>
            @else
                <div class="vote-buttons">
                    <button class="vote-btn-guest"><img src="{{ asset('images/icones/gostar.png') }}" alt="Like" class="vote-icon"></button>
                    <span class="vote-count">{{ $item['news']->newsVotes()->join('votes', 'news_votes.vote_id', '=', 'votes.id')->where('votes.value', 1)->count() }}</span>
                    <button class="vote-btn-guest"><img src="{{ asset('images/icones/nao-gosto.png') }}" alt="Dislike" class="vote-icon"></button>
                    <span class="vote-count">{{ $item['news']->newsVotes()->join('votes', 'news_votes.vote_id', '=', 'votes.id')->where('votes.value', -1)->count() }}</span>
                </div>
            @endauth
            <span class="news-comments-count">🗩 {{ $item['news']->comments()->count() }}</span>
        </div>
    </div>
</article>