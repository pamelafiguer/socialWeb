@extends('layouts.appi')

@section('title', 'Solicitudes')

@section('contenido')

<div class="container">
    <h3>Solicitudes de Amistad</h3>
    <div class="row">
        @foreach($solicitudes as $solicitud)
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="{{ $solicitud->foto_perfil }}" class="card-img-top" alt="{{ $solicitud->nombre }}">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $solicitud->nombre }}</h5>
                        <button onclick="aceptarSolicitud({{ $solicitud->id_usuario }})" class="btn btn-primary">Confirmar</button>
                        <button onclick="rechazarSolicitud({{ $solicitud->id_usuario }})" class="btn btn-secondary">Eliminar</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<script src="js/solicitudes.js"></script>


@endsection

    

