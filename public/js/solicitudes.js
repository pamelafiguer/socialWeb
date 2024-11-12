
    function aceptarSolicitud(idUsuario) {
        fetch(`/amigos/aceptar/${idUsuario}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                location.reload(); // Recarga la página para actualizar la lista de solicitudes
            } else {
                alert('Ocurrió un error');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function rechazarSolicitud(idUsuario) {
        fetch(`/amigos/rechazar/${idUsuario}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                location.reload(); // Recarga la página para actualizar la lista de solicitudes
            } else {
                alert('Ocurrió un error');
            }
        })
        .catch(error => console.error('Error:', error));
    }

