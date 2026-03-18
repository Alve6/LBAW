<article class="report list-group-item d-flex justify-content-between align-items-start mb-1" style="border-bottom:1px solid rgba(0,0,0,0.08);">
    <div class="d-flex align-items-start">
        <div class="text-center">
            <h4><a href="{{ route('report.show', ['report' => $report]) }}">Report #{{ $report->id }}</a></h4>
        </div>
    </div>
    <div class="d-flex flex-column align-items-center ms-3">
        <p>Reported user: <a href="{{ route('user.show', ['user' => $report->user]) }}">{{ $report->user->username }}</a></p>
        <p>Content: {{ Str::limit($report->content, 40) }}</p>
    </div>
</article>