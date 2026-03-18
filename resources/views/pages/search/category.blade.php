@extends('layouts.app')

@section('title', 'Category: ' . $category->name)

@section('content')
<section id="search-results">
    <h2>Category: {{ $category->name }}</h2>
    @if($news->isEmpty())
        <p>No news found in this caregory.</p>
    @else
        <div class ="news-grid">
            @foreach($news as $item)
                {{-- Reusing the existing partial --}}
                @include('partials.news', ['news' => $item, 'categories' => $item->categories])
            @endforeach
        </div>
    @endif
</section>
@endsection