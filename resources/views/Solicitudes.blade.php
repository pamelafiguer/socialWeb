@extends('layouts.appi')
@section('title', 'Solicitudes')
@section('contenido')


    <h2>Solicitudes de amistad recibidas</h2>

    <br>

    <div class="contenedor_amigos">
        @if (!empty($user))
            @foreach ($requests as $user)
                <div class="contenido_amigos">
                    <img src="{{ $user->foto_perfil ? asset('storage/public/' . $user->foto_perfil) : 'https://via.placeholder.com/100' }}"
                        alt="{{ $user->nombre }}" class="foto_perfil_amigos"
                        style="width: 150px; height: 150px; object-fit: cover">
                    <div class="nombre_solicitud">
                        <h6 class="mb-55">{{ $user->nombre }}</h6>
                    </div>
                    <button class="aceptar-solicitud" onclick="acceptFriendRequest({{ $user->id_usuario }})">Aceptar</button>
                </div>
            @endforeach
        @else
            <p>No hay solicitudes por el momento.</p>
        @endif


        <script>
            function acceptFriendRequest(senderId) {
                fetch(`/aceptarSolicitud/${senderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => alert(data.message))
                    .catch(error => console.error(error));
            }
        </script>
    @endsection
