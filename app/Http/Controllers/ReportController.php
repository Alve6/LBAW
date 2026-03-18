<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view', Report::class);
        return view('pages.admin.reports', ['reports' => Report::all()->sortByDesc('date')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Report::class);
        $user = User::find($request->input('user'));
        $target_url = $request->input('target_url');
        return view('pages.user.createreport', ['user' => $user, 'target_url' => $target_url]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Report::class);
        $request['target_url'] = rawurldecode($request->input('target_url'));
        $request->validate([
            'content' => 'required|string|max:1000',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'url',
        ]);

        $report = Report::create([
            'content' => $request->input('content'),
            'user_id' => $request->input('user_id'),
            'target_url' => $request->input('target_url'),
        ]);

        return redirect($request->input('target_url'))->with('success', 'Report submitted successfully.');
    }

    public function show(Report $report)
    {
        Gate::authorize('view', Report::class);
        return view('pages.admin.report', ['report' => $report]);
    }

    public function markAckownledged(Report $report)
    {
        Gate::authorize('acknowledge', Report::class);
        $report->acknowledgedBy()->attach(auth()->user()->id);
        $report->save();
        return redirect()->back()->with('success', 'Report marked as acknowledged.');
    }
}
