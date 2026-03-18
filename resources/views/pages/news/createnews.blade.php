@php
use App\Models\Category;
@endphp

@extends('layouts.app')

@section('title', 'Create | ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('maincontent')
    <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title">News Title (required):</label>
            <input
                id="title"
                type="text"
                name="title"
                value="{{ old('title') }}"
                required
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
                        <input type="checkbox" name="categories[]" value="{{ $category->name }}">
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <span id="categories-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
            <label for="suggested_tag" style="font-weight: bold; color: #555;">Don't see your tag?</label>
                <input 
                    type="text" 
                    name="suggested_tag" 
                    id="suggested_tag" 
                    placeholder="Suggest a new tag (e.g. AI)" 
                    class="form-control" 
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-top: 5px;"
                >
            <small style="color: #666; font-size: 0.85em; display: block; margin-top: 5px;">
                * Your suggestion will be reviewed by an admin before becoming public.
            </small>
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
            <label for="newscontent">Content (required):</label>
            <textarea
                id="newscontent"
                name="newscontent"
                rows="10"
                cols="50"
                placeholder="What do you want to tell the world?"
                required
            >{{ old('newscontent') }}</textarea>
            @error('newscontent')
                <span id="newscontent-error" class="error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3" style="text-align: center;">
            <button type="submit" class="button">Create</button>
            <a href="{{ route('allNews') }}" class="button">Cancel</a>
        </div>
    </form>
@endsection