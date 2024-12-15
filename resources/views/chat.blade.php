@extends('layouts.chat')

@section('title', 'chat')

@section('chat')
<div class="container">
    <h2>Chat con {{ $amigo->nombre }}</h2>
    <div id="chat-box" class="border p-3 mb-3" style="height: 400px; overflow-y: auto;">
        @foreach ($mensajes as $mensaje)
            <div class="{{ $mensaje->id_usuario == auth()->id() ? 'text-end' : 'text-start' }}">
                <p class="m-0"><strong>{{ $mensaje->id_usuario == auth()->id() ? 'Tú' : $amigo->nombre }}:</strong></p>
                <p class="bg-light p-2 rounded">{{ $mensaje->contenido }}</p>
                <small class="text-muted">{{ \Carbon\Carbon::parse($mensaje->fecha)->format('H:i') }}</small>
            </div>
        @endforeach
    </div>

    <form id="chat-form">
        @csrf
        <div class="input-group">
            <input type="text" id="mensaje" name="contenido" class="form-control" placeholder="Escribe un mensaje" required>
            <button type="submit" id="send-btn" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const mensajeInput = document.getElementById('mensaje');
    const chatBox = document.getElementById('chat-box');
    const idAmigo = {{ $amigo->id_usuario }};
    let ultimoMensajeId = {{ $mensajes ? $mensajes[count($mensajes)-1]->id_mensaje ?? 0 : 0 }};
    
    // Asegurarse de que el formulario no se envíe dos veces
    let isSending = false;

    // Función para formatear la hora
    function formatearHora(fechaHora) {
        const date = new Date(fechaHora);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Enviar mensaje
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const contenido = mensajeInput.value.trim();
        if (!contenido || isSending) return;


        axios.post(`/chat/enviar-mensaje/${idAmigo}`, {
            contenido: contenido
        })
        .then(response => {
            if (response.data.success) {
                const nuevoMensaje = document.createElement('div');
                nuevoMensaje.classList.add('text-end');
                nuevoMensaje.innerHTML = `
                    <p class="m-0"><strong>Tú:</strong></p>
                    <p class="bg-light p-2 rounded">${contenido}</p>
                    <small class="text-muted">${formatearHora(new Date())}</small>
                `;
                chatBox.appendChild(nuevoMensaje);
                mensajeInput.value = '';
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Error al enviar el mensaje:', error.response.data);
        })
        .finally(() => {
            // Permitir enviar de nuevo
            isSending = false;
        });
    });

    // Obtener nuevos mensajes
    function obtenerNuevosMensajes() {
        axios.get(`/chat/obtener-nuevos-mensajes/${idAmigo}?ultimo_mensaje_id=${ultimoMensajeId}`)
        .then(response => {
            if (response.data.success && response.data.mensajes.length > 0) {
                response.data.mensajes.forEach(mensaje => {
                    const nuevoMensaje = document.createElement('div');
                    nuevoMensaje.classList.add(mensaje.id_usuario == {{ auth()->id() }} ? 'text-end' : 'text-start');
                    nuevoMensaje.innerHTML = `
                        <p class="m-0"><strong>${mensaje.id_usuario == {{ auth()->id() }} ? 'Tú' : '{{ $amigo->nombre }}'}:</strong></p>
                        <p class="bg-light p-2 rounded">${mensaje.contenido}</p>
                        <small class="text-muted">${formatearHora(mensaje.fecha)}</small>
                    `;
                    chatBox.appendChild(nuevoMensaje);
                    
                    ultimoMensajeId = mensaje.id_mensaje;
                });
                
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Error al obtener nuevos mensajes:', error);
        });
    }

    // Iniciar polling cada 3 segundos
    setInterval(obtenerNuevosMensajes, 3000);
});
</script>
@endsection
