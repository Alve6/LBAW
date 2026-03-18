@extends('layouts.app')

@section('title', 'Reports' . config('app.name'))

@section('maincontent')
    <ul class="notifications-list">
        <div class="list-group">
            @foreach($reports as $report)
                <div class="mb-2">
                    @include('partials.report', ['report' => $report])
                </div>
            @endforeach
        </div>
    </ul>
@endsection