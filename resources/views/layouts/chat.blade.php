<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Social App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/principal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>

<div class="container-fluid">
    <div class="row">

        <header>
            <div class="navbar">
                <div class="navbar-left" style="height: 45px;">
                    <input type="text" id="searchInput" placeholder="Buscar">
                    <img src="/css/imgen/111.PNG" alt="" width="55" height="55"
                        style="vertical-align: middle;margin-top: -45px;margin-left: 25px;">
                </div>

                <div id="searchResults" class="SearchResults">
                    <ul id="searchResultsList" class="list-unstyled">

                    </ul>
                </div>


                <div class="navbar-center" style="margin-left: -130px;">
                    <a href="/feed" class="nav-icon active"><i class="fas fa-home"></i></a>
                    <a href="/videos" class="nav-icon"><i class="fas fa-tv"></i></a>
                    <a href="/amigos" class="nav-icon"><i class="fas fa-users"></i></a>
                    <a href="/guardado" class="nav-icon"><i class="fas fa-tv"></i></a>
                </div>
                <div class="navbar-right">

                    <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger" id="notificationCount">0</span>
                    </a>
                    <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#messageModal">
                        <i class="fas fa-envelope"></i>
                        <span class="badge bg-danger" id="unreadMessageCount">0</span> <!-- Aquí mostramos el número de mensajes no leídos -->
                    </a>
                    <a href="/Usuario" class="nav-icon">
                        <img src="{{ Auth::user()->foto_perfil ? asset('storage/public/' . Auth::user()->foto_perfil) : 'https://via.placeholder.com/100' }}"
                            class="rounded-circle me-2"
                            style="width: 30px;height: 30px;margin-top: 0.1px;/* border-radius: 100px; */; object-fit: cover;">
                        {{ Auth::check() ? Auth::user()->nombre : 'nombre' }}</a>
                </div>
            </div>
        </header>

        <div class="col-md-3 sidebar">
            <h5>Home</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="/Usuario" class="nav-link"><i class="fas fa-user"></i>
                        {{ session('usuario_nombre', 'Usuario') }}</a></li>
                <li class="nav-item"><a href="/videos" class="nav-link"><i class="fas fa-video"></i> Videos</a></li>
                <li class="nav-item"><a href="/guardado" class="nav-link"><i class="fas fa-bookmark"></i> Guardado</a>
                </li>
                <li class="nav-item"><a href="/favoritos" class="nav-link"><i class="fas fa-star"></i> Favoritos</a>
                </li>
                <li class="nav-item"><a href="/recuerdos" class="nav-link"><i class="fas fa-clock"></i> Recuerdos</a>
                </li>
                <li class="nav-item"><a href="/configuracion-y-privacidad" class="nav-link"><i class="fas fa-cog"></i>
                        Configuración y Privacidad</a></li>
                <li class="nav-item" id="ConfirmarCerrarSesion"><a href="{{ route('logout') }}" class="nav-link">Cerrar
                        Sesion</a></li>
            </ul>
        </div>

        <div class="col-md-6 main-feed">
            @yield('chat')
        </div>


        <div class="col-md-3 right-sidebar">
            <h5>Contactos</h5>
            <ul id="amigosLista" class="list-unstyleded">
            @foreach ($amigos ?? [] as $amigo)
                <li class="contact-item d-flex align-items-center mb-2">
                    <a href="{{ route('chat.index', ['idAmigo' => $amigo->id_usuario]) }}">
                        <img src="{{ $amigo->foto_perfil ? asset('storage/public/' . $amigo->foto_perfil) : 'https://via.placeholder.com/40' }}"
                            alt="Foto de {{ $amigo->nombre }}" class="rounded-circle me-2"
                            style="width: 40px; height: 40px; object-fit: cover;">
                        <span>{{ $amigo->nombre }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        </div>

    </div>

    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationsModalLabel">Notificaciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="notificationList">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para el chat -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="chat-box" class="border p-3 mb-3" style="height: 400px; overflow-y: auto;">
                    <!-- Los mensajes aparecerán aquí -->
                </div>
                <form id="chat-form">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="mensaje" class="form-control" placeholder="Escribe un mensaje">
                        <button type="button" id="send-btn" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationButton = document.querySelector('[data-bs-target="#notificationsModal"]');
        const notificationList = document.getElementById('notificationList');
        const notificationCount = document.getElementById('notificationCount');
        const searchInput = document.getElementById('searchInput');
        const searchResultsList = document.getElementById('searchResultsList');
        const amigosLista = document.getElementById("amigosLista");
        const sendBtn = document.getElementById('send-btn');
        const mensajeInput = document.getElementById('mensaje');
        const chatBox = document.getElementById('chat-box');
        const unreadMessageCount = document.getElementById('unreadMessageCount');
    
        let selectedFriendId = null;
        
        
        async function fetchApi(url, options = {}) {
            try {
                const response = await fetch(url, options);
                if (!response.ok) {
                    throw new Error(`Error del servidor: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error(`Error al realizar la solicitud a ${url}:`, error);
                throw error;
            }
        }

        
        async function searchResults() {
            const query = searchInput.value;
            if (query.length > 2) {
                try {
                    const data = await fetchApi(`/buscar-usuario?query=${encodeURIComponent(query)}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        }
                    });

                    
                    searchResultsList.innerHTML = '';
                    data.forEach(result => {
                        let resultElement = document.createElement('li');
                        resultElement.classList.add('search-result-item', 'mb-2');
                        resultElement.innerHTML = `
                        <a href="/perfil/${result.id_usuario}" class="text-decoration-none">
                            <i class="fas fa-user-circle me-2"></i> ${result.nombre}
                        </a>
                    `;
                        searchResultsList.appendChild(resultElement);
                    });
                } catch (error) {
                    searchResultsList.innerHTML = `<p class="text-danger">Error al buscar usuarios.</p>`;
                }
            } else {
                searchResultsList.innerHTML = ''; 
            }
        }

        
        searchInput.addEventListener('input', searchResults);

        
        async function loadNotifications() {
            try {
                const notifications = await fetchApi("{{ route('notifications') }}");

                notificationList.innerHTML = '';
                notificationCount.textContent = notifications.length;

                notifications.forEach(notification => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex align-items-center';

                    li.innerHTML = `
                    <img src="${notification.data.image || 'https://via.placeholder.com/40'}" class="rounded-circle me-2" alt="Notificación" style="width: 40px; height: 40px;">
                    <div class="notification-content">
                        <p class="mb-0">${notification.data.message || 'Tienes una nueva notificación'}</p>
                        <small class="text-muted">${notification.data.time || 'Hace un momento'}</small>
                    </div>
                `;
                    notificationList.appendChild(li);
                });
            } catch (error) {
                console.error('Error al cargar las notificaciones:', error);
            }
        }

        
        if (notificationButton) {
            notificationButton.addEventListener('click', loadNotifications);
        }

        async function cargarContactos() {
            try {
                const response = await fetch('/amistades', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error del servidor: ${response.status}`);
                }

                const amigos = await response.json();
                const lista = document.getElementById('amigosLista');
                lista.innerHTML = ''; // Limpiar lista anterior

                amigos.forEach(amigo => {
                    const li = document.createElement('li');
                    li.className = 'd-flex align-items-center mb-2';
                    li.innerHTML = `
                    <img src="${amigo.foto_perfil ? '/storage/public/' + amigo.foto_perfil : 'https://via.placeholder.com/40'}" 
                        alt="Foto de ${amigo.nombre}" 
                        class="rounded-circle me-2" 
                        style="width: 40px; height: 40px; object-fit: cover;">
                    <span>${amigo.nombre}</span>
                `;
                    lista.appendChild(li);
                });
            } catch (error) {
                console.error('Error al cargar los contactos:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', cargarContactos);

        
    });
</script>

</html>