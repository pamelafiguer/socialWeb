@extends('layouts.appi')
@section('title', 'Solicitudes')
@section('contenido')
    <h2>Solicitudes de amistad recibidas</h2>
    @foreach ($requests as $user)
        <div>
            <span>{{ $user->nombre }}</span>
            <button onclick="acceptFriendRequest({{ $user->id_usuario }})">Aceptar</button>
        </div>
    @endforeach

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