@extends('layouts.app')

@section('title', 'Manage Tags')

@section('maincontent')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1>Manage Content Tags</h1>

    @if(session('success'))
        <div class="alert alert-success" style="padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger" style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #ddd;">
        <h3>Add New Tag</h3>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">Type a name below to create a new official tag.</p>

        <form action="{{ route('admin.tags.store') }}" method="POST" style="display: flex; gap: 10px;">
            @csrf
            <input
                type="text"
                name="name"
                placeholder="e.g., Technology"
                required
                style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <button type="submit" class="button" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer">
                + Create Tag
            </button>
        </form>
    </div>

    <h3>Existing Tags</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="babckground: #eee; text-align: left;">
                <th style="padding: 10px; border-bottom: 2px solid #ddd;">Tag Name</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px; font-weight: bold;">
                    {{ $tag->name }}
                </td>
                <td style="padding: 15px; text-align: right;">
                    <form action="{{ route('admin.tags.destroy', ['category' => $tag->name]) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure? This will remove the tag from all news articles.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button" style="padding: 5px 15px; font-size: 0.8rem; background-color: #dc3545; color: white; border: none; border-radius: 3px;, cursor: pointer;">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

