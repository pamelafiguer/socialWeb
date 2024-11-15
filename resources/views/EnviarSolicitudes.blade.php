@extends('layouts.appi')
@section('title', 'Enviar-solicitudes')
@section('contenido')
    <h2>Enviar solicitud de amistad</h2>
    @foreach ($users as $user)
        <div>
            <span>{{ $user->nombre }}</span>
            <button onclick="sendFriendRequest({{ $user->id_usuario }})">Enviar Solicitud</button>
        </div>
    @endforeach

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
