@extends('layouts.appi')

@section('title', 'friends')

@section('contenido')


    <h2>Mis Amigos</h2>
    <br>
    <div class="contenedor_amigos">

        @foreach ($friends as $friend)
            <div class="contenido_amigos">
                <img src="{{ $friend->foto_perfil ? asset('storage/public/' . $friend->foto_perfil) : 'https://via.placeholder.com/100' }}"
                    alt="{{ $friend->nombre }}" class="foto_perfil_amigos" style="width: 150px; height: 150px;">
                <div class="nombre_solicitud">
                    <h6 class="mb-55">{{ $friend->nombre }}</h6>
                </div>

            </div>
        @endforeach

    </div>
@endsection
