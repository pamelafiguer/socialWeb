@extends('layouts.appi')
@section('title', 'Enviar-solicitudes')
@section('contenido')

    <h2>Enviar solicitud de amistad</h2>
    <br>

    <div class="contenedor_amigos">

        @foreach ($users as $user)
            <div class="contenido_amigos">
                <img src="{{ $user->foto_perfil ? asset('storage/public/' . $user->foto_perfil) : 'https://via.placeholder.com/100' }}"
                    alt="{{ $user->nombre }}" class="foto_perfil_amigos" style="width: 150px; height: 150px;">
                <div class="nombre_solicitud">
                    <h6 class="mb-55">{{ $user->nombre }}</h6>
                </div>
                <button class="enviar-solicitud" onclick="sendFriendRequest({{ $user->id_usuario }})">Enviar Solicitud</button>
            </div>
            
        @endforeach

    </div>



    <script>
        function sendFriendRequest(receiverId) {
            fetch(`/enviarSolicitud/${receiverId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.message === 'Solicitud enviada exitosamente') {
                        // Opcional: recargar la página o actualizar la UI
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar la solicitud');
                });
        }
    </script>
@endsection
