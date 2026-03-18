@php
use App\Models\Category;
@endphp

@extends('layouts.app')

@section('title', 'Edit ' . $news->title . '|' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
    <form method="POST" action="{{ route('news.update', ['news' => $news]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="title">News Title</label>
            <input
                id="title"
                type="text"
                name="title"
                placeholder="{{ $news->title }}"
                autofocus
            >
            @error('title')
                <span id="title-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label>Choose applicable categories:</label>
            <div class="categories-grid">
                @foreach (Category::all() as $category)
                    <label class="category-checkbox">
                        <input type="checkbox" name="categories[]" value="{{ $category->name }}"
                            {{ $news->categories->contains($category) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <span id="categories-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="file">Image</label>
            <input name="file" type="file" id="file">
            <input name="type" type="text" value="news" hidden>
            @error('file')
                <span id="file-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        
        <div class="mb-3">
            <label for="newscontent">Content</label>
            <textarea
                id="newscontent"
                name="newscontent"
                rows="10"
                cols="50"
                placeholder="What do you want to tell the world?"
            >{{ $news->content }}</textarea>
            @error('newscontent')
                <span id="newscontent-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3" style="text-align: center;">
            <button type="submit" class="button">Edit</button>
            <a href="{{ route('news.show', ['news' => $news]) }}" class="button">Cancel</a>
        </div>
        
    </form>
@endsection