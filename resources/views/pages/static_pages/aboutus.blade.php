@extends('layouts.app')

@section('title', 'About Us | ' . config('app.name'))

@section('maincontent')

<div class="text-center text-wrap">
    <h1>About Us</h1>
</div>

<div class="text-justify text-wrap">
    <p>Welcome to NoMisHub. We are glad to have you here.</p>
    <p>Our goal is to create a platform where users can share news, 
        while fact-checkers and administrators ensure that content is properly sourced, 
        and where discussion is fostered among people from diverse backgrounds, perspectives and beliefs.
        And we are happy to have you, now, share this goal with us.
    </p>
</div>

<div class="text-center text-wrap">
    <h4>Our mission is to promote No-Mis-Information and No-Miss-Information.</h4>
</div>

<div class="text-justify text-wrap">
    <p>You can check the tools we have available to you in the <a href="{{ route('features') }}">Main Features</a>
        page and contact us through our emails available in the <a href="{{ route('contacts') }}">Contacts</a> page.</p>
</div>
<div class="text-center text-wrap h5">
    <p>Have fun!!!</p>
</div>
@endsection