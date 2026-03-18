@extends('layouts.app')

@section('title', 'Contacts | ' . config('app.name'))

@section('maincontent')

<div class="text-center text-wrap">
    <h1>Contacts</h1>
</div>

<div class="text-justify text-wrap">
    <p>Bellow are the emails of the developers of this project.</p>
    <p>Feel free to contact them and give your feedback.</p>
    <ul>
        <li>Ana Catarina Barbosa Patrício, up202107383@fe.up.pt</li>
        <li>António Pedro Alves Pais, up202305444@fe.up.pt</li>
        <li>Hugo Ribeiro Alves, up202305395@fe.up.pt</li>
        <li>Tomás Pereira da Silva, up202307796@fe.up.pt</li>
    </ul>

    <p>Read more about our mission in the <a href="{{ route('aboutus') }}">About Us</a> page and check 
        the tools we have available to you in the <a href="{{ route('features') }}">Main Features</a> page.</p>
</div>
<div class="text-center text-wrap h5">
    <p>We look forward to hearing from you!</p>
</div>

@endsection