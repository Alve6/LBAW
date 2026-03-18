@extends('layouts.app')

@section('title', 'Report #' . $report->id . ' | ' . config('app.name'))

@section('maincontent')
    <section class="container my-4">
        <div class="row justify-content-center card shadow-sm col-lg-10">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Report #{{ $report->id }}</h2>
            </div>

            <div class="card-body">
                <p class="mb-2"><strong>Reported user:</strong>
                    <a href="{{ route('user.show', ['user' => $report->user]) }}">{{ $report->user->username }}</a>
                </p>

                <div class="mb-3">
                    <h3 class="h6">Content:</h3>
                    <div class="border rounded p-2 bg-white">{{ $report->content }}</div>
                </div>

                @if(!is_null($report->target_url))
                    <div class="mb-3">
                        <h3 class="h6">Reported content url:</h3>
                        <div><a href="{{ $report->target_url }}">{{ $report->target_url }}</a></div>
                    </div>
                @endif

                <div class="mb-3">
                    <h3 class="h6">Acknowledged by:</h3>
                    @if ($report->acknowledgedBy()->get()->isNotEmpty())
                        <ul>
                        @foreach ($report->acknowledgedBy()->get() as $admin)
                            @if($admin->user)
                                <li>
                                    <a href="{{ route('user.show', ['user' => $admin->user]) }}">{{ $admin->user->username }}</a>
                                </li>
                            @endif
                        @endforeach
                        </ul>
                    @else
                        <div>Not yet acknowledged</div>
                    @endif
                </div>

                <form method="POST" action="{{ route('report.acknowledge', ['report' => $report]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Mark as Acknowledged</button>
                </form>
            </div>
        </div>
    </section>
@endsection
