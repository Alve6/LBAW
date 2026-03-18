@extends('layouts.app')

@section('title',  'Homepage | ' . config('app.name'))

@push('scripts')
    <script src="{{ asset('js/search-all.js') }}" defer></script>
    <script src="{{ asset('js/comments.js') }}" defer></script>
    <script src="{{ asset('js/votes.js') }}" defer></script>
    <script src="{{ asset('js/moderator.js') }}" defer></script>
@endpush

    
@section('maincontent')
    <div class="search-create-container" style="display: flex; align-items: center; gap: 10px; max-width: 890px; margin: 0 auto 20px auto; position: relative; z-index: 1000;">
        
        <div class="searchbar" style="flex-grow: 1;">
            <form id="searchForm" action="{{ route('allNews') }}" method="GET" style="display: grid; grid-template-columns: 1fr auto auto; align-items: stretch; width: 100%; border: 2px solid #000; border-radius: 5px; background: white; height: 50px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 0; margin: 0;">                
                
                <input 
                    type="text" 
                    name="search" 
                    placeholder="🔍︎ Search..." 
                    value="{{ request('search') }}"
                    autocomplete="off" 
                    style="width: 100%; min-width: 0; border: none; padding: 0 20px; font-size: 1rem; outline: none; background: transparent; height: 100%; margin: 0; border-radius: 5px 0 0 5px;"                
                >

                <div style="position: relative; border-left: 1px solid #ccc; height: 100%;">
                    <button type="button" onclick="toggleFilters()" style="height: 100%; border: none; background: #e6e3e3; padding: 0 20px; cursor: pointer; font-size: 0.9rem; font-weight: 600; color: #333; white-space: nowrap; margin: 0;">
                        Filters ▾
                    </button>
                    
                    <div id="filterMenu" style="display: none; position: absolute; top: 105%; right: 0; width: 250px; background: white; border: 2px solid #000; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 2000; padding: 15px; text-align: left;">

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Sort By:</label>
                            <select name="sort" style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
                                <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Newest (Default)</option>
                                <option value="upvotes" {{ request('sort') == 'upvotes' ? 'selected' : '' }}>Most Upvoted</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Verified:</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem; cursor: pointer; padding: 5px; border: 1px solid #eee; border-radius: 3px; background: #f9f9f9;">
                                <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }} style="cursor: pointer;">
                                <span>Show Verified Only ✅</span>
                            </label>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Author:</label>
                            <input type="text" name="author" value="{{ request('author') }}" placeholder="Username..." style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Category:</label>
                            <input type="text" name="category" value="{{ request('category') }}" placeholder="Tag name..." style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="button" style="flex: 1; padding: 8px; font-size: 0.9rem; cursor: pointer;">
                                Apply
                            </button>
                            
                            <a href="{{ route('allNews') }}" class="button" style="flex: 1; text-align: center; text-decoration: none; padding: 8px; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center; background-color: #6c757d; color: white; border: none;">
                                Clear
                            </a>
                        </div>
                    </div>
                </div>
                
                <button 
                    type="submit" 
                    class="button" 
                    style="border: none; border-radius: 0 5px 5px 0; margin: 0; padding: 0 30px; cursor: pointer; height: 100%; background-color: #c9c9c9; color: #000; font-weight: 500; font-size: 1.1rem; border-left: 1px solid #999;"                
                >
                    Search
                </button>
            </form>
        </div>

        @auth
            <a href="{{ route('news.create') }}" class="create-news-btn" title="Create News">+</a>
        @endauth
    </div>
    
    <div id="news-container" style="position: relative; z-index: 1; min-height: 600px;">
        @foreach($news_categories as $item)
            @include('partials.news', $item)
        @endforeach
    </div>

    <script>
        window.toggleFilters = function() {
            var menu = document.getElementById('filterMenu');
            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        };

        document.addEventListener('click', function(event) {
            var menu = document.getElementById('filterMenu');
            var button = document.querySelector('button[onclick="toggleFilters()"]');
            
            if (menu && menu.style.display === 'block') {
                if (!menu.contains(event.target) && !button.contains(event.target)) {
                    menu.style.display = 'none';
                }
            }
        });
    </script>
@endsection