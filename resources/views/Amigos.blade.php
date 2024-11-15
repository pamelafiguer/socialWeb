@extends('layouts.appi')

@section('title', 'friends')

@section('contenido')


<h2>Mis Amigos</h2>
<ul>
    @foreach ($friends as $friend)
        <li>{{ $friend->nombre }}</li>
    @endforeach
</ul>




@endsection

    

