<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'App' )</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
</head>

    <div class="container-fluid">
        <div class="row">

            <header>
                <div class="navbar">
                    <div class="navbar-left" style="height: 45px;">
                        <input type="text" id="searchInput" placeholder="Search">
                        <img src="/css/imgen/111.PNG" alt="" width="55" height="55"
                            style="vertical-align: middle;margin-top: -55px;margin-left: 25px;">
                    </div>
                    <div id="searchResults" class="SearchResults">
                        <ul id="searchResultsList" class="list-unstyled">
    
                        </ul>
                    </div>
                    <div class="navbar-center">
                        <a href="/feed" class="nav-icon active"><i class="fas fa-home"></i></a>
                        <a href="/videos" class="nav-icon"><i class="fas fa-tv"></i></a>
                        <a href="/amigos" class="nav-icon"><i class="fas fa-users"></i></a>
                        
                    </div>
                    <div class="navbar-right">
                    
                        <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger" id="notificationCount">3</span>
                        </a>
                        <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#messageModal">
                            <i class="fas fa-envelope"></i></a>
                        <a href="/Usuario" class="nav-icon">
                            <img src="{{ Auth::user()->foto_perfil ? asset('storage/public/' . Auth::user()->foto_perfil) : 'https://via.placeholder.com/100' }}"
                                        class="rounded-circle me-2" style="width: 30px;height: 30px;margin-top: 0.1px;/* border-radius: 100px; */; object-fit: cover;">
                            {{ Auth::check() ? Auth::user()->nombre : 'nombre' }}</a>
                    </div>
                </div>
            </header>

        

            <div class="col-md-6 main-feed">
                @yield('contenido-usuario')
            </div>

        </div>
    </div>
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"  crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.querySelector('[data-bs-target="#notificationsModal"]');
            const notificationList = document.getElementById('notificationList');
            const notificationCount = document.getElementById('notificationCount');
            const searchInput = document.getElementById('searchInput');
            const searchResultsList = document.getElementById('searchResultsList');
    
            
            function searchResults() {
                const query = searchInput.value;
                console.log('Search query:', query);
    
                if (query.length > 2) {
                    fetch(`/buscar-usuario?query=${encodeURIComponent(query)}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Error del servidor: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                searchResultsList.innerHTML =
                                    `<p class="text-danger">${data.error}</p>`;
                                return;
                            }
    
                            searchResultsList.innerHTML = ''; 
                            data.forEach(result => {
                                let resultElement = document.createElement('li');
                                resultElement.classList.add('search-result-item', 'mb-2');
                                resultElement.innerHTML = `
                            <a href="/perfil/${result.id_usuario}" class="text-decoration-none text-dark">
                                <i class="fas fa-user-circle me-2"></i> ${result.nombre}
                            </a>
                        `;
                                searchResultsList.appendChild(resultElement); 
                            });
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(html => {
                                    console.error('Error del servidor:', html);
                                    throw new Error('La respuesta no es JSON válida.');
                                });
                            }
                            return response.json();
                        })
                } else {
                    searchResultsList.innerHTML = ''; 
                }
            }
    
    
    
            
            searchInput.addEventListener('input', searchResults);
    
    
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Backspace') {
                    document.getElementById('searchResults').style.display = 'none';
                }
            });
    

            async function loadNotifications() {
                try {
                    const response = await fetch("{{ route('notifications') }}");
                    const notifications = await response.json();
    
                    notificationList.innerHTML = '';
                    notificationCount.textContent = notifications.length;
    
                    notifications.forEach(notification => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex align-items-center';
    
                        const img = document.createElement('img');
                        img.src = notification.data.image || 'https://via.placeholder.com/40';
                        img.className = 'rounded-circle me-2';
                        img.style.width = '40px';
                        img.style.height = '40px';
    
                        const content = document.createElement('div');
                        content.className = 'notification-content';
    
                        const message = document.createElement('p');
                        message.className = 'mb-0';
                        message.textContent = notification.data.message ||
                            'Tienes una nueva notificación';
    
                        const time = document.createElement('small');
                        time.className = 'text-muted';
                        time.textContent = notification.data.time || 'Hace un momento';
    
                        content.appendChild(message);
                        content.appendChild(time);
                        li.appendChild(img);
                        li.appendChild(content);
    
                        notificationList.appendChild(li);
                    });
                } catch (error) {
                    console.error('Error al cargar las notificaciones:', error);
                }
            }
    

            notificationButton.addEventListener('click', loadNotifications);
        });
    </script>
</html>