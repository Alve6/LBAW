<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
        <link rel="stylesheet" href="{{ asset('css/news.css') }}">
        @stack('styles')

        <!-- Scripts -->
        <!-- Is this script actually needed? -->
        <!-- I think so, mas não checkei tudo-->
        <script src="{{ asset('js/bootstrap.js') }}" defer></script>
        <script src="{{ asset('js/follow.js') }}" defer></script>
        @stack('scripts')

    </head>
    <body>
        <main>
            <div class="pageframe">
                <div class="sidebar"> 
                    <div class="logo">
                        <a href="{{ route('homepage') }}" class="logo-link">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image">
                            <h1>NoMisHub</h1>
                        </a>
                    </div>
                    
                    @auth
                        <div class="profile_info">
                            <img src="{{ Auth::user()->getProfileImage() }}" alt="Profile Picture" class="profilepicture">
                            <a href="{{ route('user.show', ['user' => Auth::user()]) }}" class="username">
                                <h2>{{ Auth::user()->username }}</h2>
                            </a>
                        </div>
                    @else
                        <div class="profile_info">
                            <a href="{{ route('login') }}"><button class="button"><strong>LOGIN</strong></button></a>
                        </div>
                    @endauth
                    
                    <div class="profile_news">
                        @auth
                            <div class="news-scroll-section">
                                <h3>My News</h3>
                                <div class="news-scroll">
                                    @forelse (Auth::user()->news()->orderBy('date', 'desc')->take(10)->get() as $news)
                                        <a href="{{ route('news.show', ['news' => $news]) }}" class="news-item-link">
                                            <img src="{{ $news->getImage() }}" alt="{{ $news->title }}" class="news-item-image">
                                            <span class="news-item-title">{{ Str::limit($news->title, 40) }}</span>
                                        </a>
                                    @empty
                                        <p class="no-news-text">No news found.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="news-scroll-section">
                                <h3>Favourite News</h3>
                                <div class="news-scroll">
                                    @php
                                        $favouriteNewsIds = Auth::user()->news_votes()
                                            ->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                                            ->where('votes.value', 1)
                                            ->orderBy('votes.date', 'desc')
                                            ->take(10)
                                            ->pluck('news_votes.news_id');
                                        
                                        $favouriteNews = \App\Models\News::whereIn('id', $favouriteNewsIds)
                                            ->orderBy('date', 'desc')
                                            ->get();
                                    @endphp
                                    @forelse ($favouriteNews as $newsItem)
                                        <a href="{{ route('news.show', ['news' => $newsItem]) }}" class="news-item-link">
                                            <img src="{{ $newsItem->getImage() }}" alt="{{ $newsItem->title }}" class="news-item-image">
                                            <span class="news-item-title">{{ Str::limit($newsItem->title, 40) }}</span>
                                        </a>
                                    @empty
                                        <p class="no-news-text">No favourites yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endauth 
                        
                        <div style="margin-top: auto; padding-top: 20px;">
                            <a href="{{ route('allNews') }}" class="sidebar-text-link">
                                News Page
                                <img src="{{ asset('images/icones/jornal.png') }}" alt="News" class="sidebar-icon">
                            </a>
                        </div>
                        <div style="margin-top: auto;">
                            @auth
                                <div style="margin-top: auto;">
                                    <a href="{{ route('notifications.index', ['user' => Auth::user()]) }}" class="sidebar-text-link">
                                        Notifications
                                        <img src="{{ asset('images/icones/sino.png') }}" alt="Notifications" class="sidebar-icon">
                                    </a>
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <div style="margin-top: auto;">
                                        <a href="{{ route('admin.tags.index') }}" class="sidebar-text-link">
                                            Manage Tags
                                            <img src="{{ asset('images/icones/tag.png') }}" alt="Tags" class="sidebar-icon">
                                        </a>
                                    </div>
                                    <div style="margin-top: auto;">
                                        <a href="{{ route('reports.index') }}" class="sidebar-text-link">
                                            Reports
                                            <img src="{{ asset('images/icones/falando.png') }}" alt="Reports" class="sidebar-icon">
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                    
                    <div class="static d-flex flex-row flex-wrap">
                        <a href="{{ route('aboutus') }}" class="sidebar-text-link">About Us</a>
                        <a href="{{ route('contacts') }}" class="sidebar-text-link">Contacts</a>
                        <a href="{{ route('features') }}" class="sidebar-text-link">Features</a>
                        @auth
                            <a href="{{ route('logout') }}" class="sidebar-text-link">
                                Logout
                                <img src="{{ asset('images/icones/botao-de-logout.png') }}" alt="Logout" class="sidebar-icon">
                            </a>
                        @endauth
                    </div>
                </div>
                <!-- Are we to keep this? -->
                <section class="messages">
                    @if(session('success'))
                        <div class="messages-container">
                            @if(session('success'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                                @php
                                    session()->forget('success');
                                @endphp
                            @endif
                        </div>
                    @endif
                </section>
                <section class="maincontent" id="maincontent">
                    @yield('maincontent')
                </section>
            </div>
        </main>
    </body>
</html>