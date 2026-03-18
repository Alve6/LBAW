@extends('layouts.app')

@section('title', 'Main Features | ' . config('app.name'))

@section('maincontent')

<div class="text-center text-wrap">
    <h1>Main Features</h1>
    <h5>Our website has the following features currently available to you:</h5>
</div>
<div class="text-justify text-wrap">
    <ul>
        <li>Manage your published news pieces;</li>
        <li>Manage your profile and comments;</li>
        <li>A capable search system that allows you to search for content that might interest you easily,
            mainly supported by full-text search;</li>
        <li>Password recovery capabilities;</li>
        <li>Vote on news pieces and comments based on your opinion on them;</li>
        <li>Share your opinions with others through comments;</li>
        <li>Report content that violates our policies and help keep everyone safe;</li>
        <li>Rely on our team of administrators to keep everything running smoothly;</li>
        <li>And on our moderators to check how reliable the content is (you will see fact-checked content marked with a ✓)</li>
        <li>And much more to come...</li>
    </ul>
    <p>If you have any new ideas or suggestions, feel free to contact us through the <a href="{{ route('contacts') }}">Contacts</a> page, 
    and if you want to learn more about our project, check the <a href="{{ route('aboutus') }}">About Us</a> page.</p>
</div>
<div class="text-center text-wrap h5">
    <p>We hope you enjoy using our platform!</p>
</div>

@endsection